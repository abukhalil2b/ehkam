<?php

namespace App\Services;

use App\Contracts\HasWorkflow;
use App\Models\WorkflowTransition;
use App\Models\User;
use App\Models\WorkflowStage;
use App\Events\StepSubmitted;
use App\Events\StepApproved;
use App\Events\StepReturned;
use App\Events\StepRejected;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WorkflowService
{
    /**
     * Submit a model to its workflow (moves from draft to first stage)
     *
     * @param HasWorkflow&Model $model
     * @param User $actor
     * @param string|null $comments
     * @return HasWorkflow&Model
     * @throws \Exception
     */
    public function submitStep(Model $model, User $actor, ?string $comments = null): Model
    {
        if (!$model instanceof HasWorkflow) {
            throw new \InvalidArgumentException('Model must implement HasWorkflow interface.');
        }

        return DB::transaction(function () use ($model, $actor, $comments) {
            // Lock the model row to prevent concurrent transitions (and instance)
            $model = $model->newQuery()->where('id', $model->id)->lockForUpdate()->first();
            $instance = $model->workflowInstance;

            if (!$instance || !$instance->workflow_id) {
                throw new \Exception('Model must be assigned to a workflow before submission.');
            }

            if (!$instance->isDraft()) {
                throw new \Exception('Only draft items can be submitted.');
            }

            $firstStage = $instance->workflow->firstStage();

            if (!$firstStage) {
                throw new \Exception('Workflow has no stages defined.');
            }

            // Record the transition (attached to the model, or instance? Keeping strictly to model for history)
            $model->transitions()->create([
                'actor_id' => $actor->id,
                'from_stage_id' => null,
                'to_stage_id' => $firstStage->id,
                'action' => 'submit',
                'comments' => $comments,
            ]);

            // Calculate stage due date if applicable
            $dueAt = null;
            if ($firstStage->allowed_days) {
                $dueAt = now()->addDays($firstStage->allowed_days);
            }

            // Move to first stage
            $instance->update([
                'current_stage_id' => $firstStage->id,
                'status' => 'in_progress',
                'stage_due_at' => $dueAt,
            ]);

            // Dispatch event for notifications
            event(new StepSubmitted($model, $actor, $firstStage));

            return $model->fresh();
        });
    }

    /**
     * Approve a model (moves to next stage or completes the workflow)
     *
     * @param HasWorkflow&Model $model
     * @param User $actor
     * @param string|null $comments
     * @return HasWorkflow&Model
     * @throws \Exception
     */
    public function approveStep(Model $model, User $actor, ?string $comments = null): Model
    {
        if (!$model instanceof HasWorkflow) {
            throw new \InvalidArgumentException('Model must implement HasWorkflow interface.');
        }

        return DB::transaction(function () use ($model, $actor, $comments) {
            // Lock the model row
            $model = $model->newQuery()->where('id', $model->id)->lockForUpdate()->first();
            $instance = $model->workflowInstance;

            // Verify using instance data
            $this->verifyUserCanAct($model, $actor);

            $currentStage = $instance->currentStage;

            if (!$currentStage->can_approve) {
                throw new \Exception('Approval is not allowed at this stage.');
            }

            // Find the next stage
            $nextStage = $currentStage->nextStage();

            // Record the transition
            $model->transitions()->create([
                'actor_id' => $actor->id,
                'from_stage_id' => $currentStage->id,
                'to_stage_id' => $nextStage?->id,
                'action' => 'approve',
                'comments' => $comments,
            ]);

            if ($nextStage) {
                // Calculate stage due date if applicable
                $dueAt = null;
                if ($nextStage->allowed_days) {
                    $dueAt = now()->addDays($nextStage->allowed_days);
                }

                // Move to next stage
                $instance->update([
                    'current_stage_id' => $nextStage->id,
                    'status' => 'in_progress',
                    'stage_due_at' => $dueAt,
                ]);
            } else {
                // No next stage - workflow is complete
                $instance->update([
                    'current_stage_id' => null,
                    'status' => 'completed',
                ]);
            }

            // Dispatch event for notifications
            event(new StepApproved($model, $actor, $nextStage));

            return $model->fresh();
        });
    }

    /**
     * Return a model to a previous stage (or a specific stage)
     *
     * @param HasWorkflow&Model $model
     * @param User $actor
     * @param int|null $targetStageId Specific stage to return to (null = previous stage)
     * @param string|null $comments
     * @return HasWorkflow&Model
     * @throws \Exception
     */
    public function returnStep(
        Model $model,
        User $actor,
        ?int $targetStageId = null,
        ?string $comments = null,
        array $stepFeedbacks = []
    ): Model {
        if (!$model instanceof HasWorkflow) {
            throw new \InvalidArgumentException('Model must implement HasWorkflow interface.');
        }

        return DB::transaction(function () use ($model, $actor, $targetStageId, $comments, $stepFeedbacks) {
            // Lock the model row
            $model = $model->newQuery()->where('id', $model->id)->lockForUpdate()->first();
            $instance = $model->workflowInstance;

            $this->verifyUserCanAct($model, $actor);
            $currentStage = $instance->currentStage;

            if (!$currentStage->can_return) {
                throw new \Exception('Return is not allowed at this stage.');
            }

            // Determine target stage
            if ($targetStageId) {
                $prevStage = WorkflowStage::find($targetStageId);

                if (!$prevStage) {
                    throw new \Exception('Target stage not found.');
                }

                if ($prevStage->workflow_id !== $instance->workflow_id) {
                    throw new \Exception('Target stage must belong to the same workflow.');
                }

                if ($prevStage->order >= $currentStage->order) {
                    throw new \Exception('Target stage must be before the current stage.');
                }
            } else {
                // Default to previous stage
                $prevStage = $currentStage->previousStage();
            }

            // Record the transition
            $transition = $model->transitions()->create([
                'actor_id' => $actor->id,
                'from_stage_id' => $currentStage->id,
                'to_stage_id' => $prevStage?->id,
                'action' => 'return',
                'comments' => $comments,
            ]);

            // Save step feedbacks
            if (!empty($stepFeedbacks)) {
                foreach ($stepFeedbacks as $stepId => $note) {
                    $transition->feedbacks()->create([
                        'step_id' => $stepId,
                        'notes' => $note,
                        'created_by' => $actor->id,
                    ]);
                }
            }

            // Move to previous stage
            // Recalculate deadline for the returned stage (optional: could be 0 or reuse days)
            $dueAt = null;
            if ($prevStage && $prevStage->allowed_days) {
                $dueAt = now()->addDays($prevStage->allowed_days);
            }

            $instance->update([
                'current_stage_id' => $prevStage?->id,
                'status' => $prevStage ? 'returned' : 'in_progress',
                'stage_due_at' => $dueAt,
            ]);

            // Dispatch event for notifications
            event(new StepReturned($model, $actor, $prevStage));

            return $model->fresh();
        });
    }

    /**
     * Reject a model (terminates the workflow)
     *
     * @param HasWorkflow&Model $model
     * @param User $actor
     * @param string|null $comments
     * @return HasWorkflow&Model
     * @throws \Exception
     */
    public function rejectStep(Model $model, User $actor, ?string $comments = null): Model
    {
        if (!$model instanceof HasWorkflow) {
            throw new \InvalidArgumentException('Model must implement HasWorkflow interface.');
        }

        return DB::transaction(function () use ($model, $actor, $comments) {
            // Lock the model row
            $model = $model->newQuery()->where('id', $model->id)->lockForUpdate()->first();
            $instance = $model->workflowInstance;

            $this->verifyUserCanAct($model, $actor);

            // Record the transition
            $model->transitions()->create([
                'actor_id' => $actor->id,
                'from_stage_id' => $instance->current_stage_id,
                'to_stage_id' => null,
                'action' => 'reject',
                'comments' => $comments,
            ]);

            // Mark as rejected (terminal state)
            $instance->update([
                'current_stage_id' => null,
                'status' => 'rejected',
            ]);

            // Dispatch event for notifications
            event(new StepRejected($model, $actor));

            return $model->fresh();
        });
    }

    /**
     * Verify the user has permission to act on the model
     *
     * @param HasWorkflow&Model $model
     * @param User $actor
     * @throws \Exception
     */
    protected function verifyUserCanAct(Model $model, User $actor): void
    {
        $instance = $model->workflowInstance;

        if (!$instance || $instance->isTerminal()) {
            throw new \Exception('Cannot act on a completed or rejected item.');
        }

        if (!$instance->current_stage_id) {
            throw new \Exception('Item has no current stage.');
        }

        $teamId = $instance->currentStage->team_id;
        $userInTeam = $actor->workflowTeams()->where('workflow_teams.id', $teamId)->exists();

        if (!$userInTeam) {
            abort(403, 'You are not authorized to act on this item.');
        }
    }

    /**
     * Get all steps pending action by a specific user
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingStepsForUser(User $user)
    {
        return $user->pendingWorkflowActivities();
    }

    /**
     * Assign a model to a workflow and optionally submit it
     *
     * @param HasWorkflow&Model $model
     * @param int $workflowId
     * @param User $actor
     * @param bool $autoSubmit
     * @return HasWorkflow&Model
     */
    public function assignWorkflow(Model $model, int $workflowId, User $actor, bool $autoSubmit = false): Model
    {
        if (!$model instanceof HasWorkflow) {
            throw new \InvalidArgumentException('Model must implement HasWorkflow interface.');
        }

        // Create or update workflow instance
        $instance = $model->workflowInstance()->updateOrCreate(
            ['workflowable_type' => $model->getMorphClass(), 'workflowable_id' => $model->id], // Conditions
            [
                'workflow_id' => $workflowId,
                'status' => 'draft',
                'current_stage_id' => null,
                'creator_id' => $actor->id
            ]
        );

        if ($autoSubmit) {
            return $this->submitStep($model->fresh(), $actor);
        }

        return $model->fresh();
    }
}
