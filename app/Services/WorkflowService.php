<?php

namespace App\Services;

use App\Contracts\HasWorkflow;
use App\Models\User;
use App\Models\WorkflowStage;
use App\Events\StepSubmitted;
use App\Events\StepApproved;
use App\Events\StepReturned;
use App\Events\StepRejected;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Exception;

class WorkflowService
{
    /**
     * Assign a model to a workflow and optionally submit it.
     *
     * @param HasWorkflow&Model $model
     * @param int $workflowId
     * @param User $actor
     * @param bool $autoSubmit
     * @return Model
     */
    public function assignWorkflow(Model $model, int $workflowId, User $actor, bool $autoSubmit = false): Model
    {
        $this->ensureImplementor($model);

        DB::transaction(function () use ($model, $workflowId, $actor) {
            $model->workflowInstance()->updateOrCreate(
                ['workflowable_type' => $model->getMorphClass(), 'workflowable_id' => $model->id],
                [
                    'workflow_id' => $workflowId,
                    'status' => 'draft',
                    'current_stage_id' => null,
                    'creator_id' => $actor->id,
                ]
            );
        });

        // Refresh model to load the relation
        $model = $model->fresh();

        if ($autoSubmit) {
            return $this->submitStep($model, $actor);
        }

        return $model;
    }

    /**
     * Submit a model to its workflow (moves from draft to first stage).
     */
    public function submitStep(Model $model, User $actor, ?string $comments = null): Model
    {
        $this->ensureImplementor($model);

        return DB::transaction(function () use ($model, $actor, $comments) {
            $model = $this->lockModel($model);
            $instance = $model->workflowInstance;

            if (!$instance || !$instance->workflow_id) {
                throw new Exception('Model must be assigned to a workflow before submission.');
            }

            // Allow submission from draft or returned status (for resubmission)
            if (!in_array($instance->status, ['draft', 'returned'])) {
                throw new Exception('Only draft or returned items can be submitted.');
            }

            $firstStage = $instance->workflow->firstStage();
            if (!$firstStage) {
                throw new Exception('Workflow has no stages defined.');
            }

            $this->recordTransition($model, $actor, null, $firstStage->id, 'submit', $comments);

            $dueAt = $firstStage->allowed_days ? now()->addDays($firstStage->allowed_days) : null;

            $instance->update([
                'current_stage_id' => $firstStage->id,
                'status' => 'in_progress',
                'stage_due_at' => $dueAt,
            ]);

            // Update Step model status to 'review' when submitted to workflow
            if ($model instanceof \App\Models\Step) {
                $model->update(['status' => 'review']);
            }

            event(new StepSubmitted($model, $actor, $firstStage));

            return $model->fresh();
        });
    }

    /**
     * Approve a model (moves to next stage or completes the workflow).
     */
    public function approveStep(Model $model, User $actor, ?string $comments = null): Model
    {
        $this->ensureImplementor($model);

        return DB::transaction(function () use ($model, $actor, $comments) {
            $model = $this->lockModel($model);
            $instance = $model->workflowInstance;

            $this->verifyUserCanAct($model, $actor);

            $currentStage = $instance->currentStage;
            if (!$currentStage->can_approve) {
                throw new Exception('Approval is not allowed at this stage.');
            }

            $nextStage = $currentStage->nextStage();

            $this->recordTransition($model, $actor, $currentStage->id, $nextStage?->id, 'approve', $comments);

            if ($nextStage) {
                $dueAt = $nextStage->allowed_days ? now()->addDays($nextStage->allowed_days) : null;
                $instance->update([
                    'current_stage_id' => $nextStage->id,
                    'status' => 'in_progress',
                    'stage_due_at' => $dueAt,
                ]);
                // Keep Step status as 'review' when moving to next stage
                if ($model instanceof \App\Models\Step) {
                    $model->update(['status' => 'review']);
                }
            } else {
                $instance->update([
                    'current_stage_id' => null,
                    'status' => 'completed',
                ]);
                // Update Step status to 'completed' when workflow is completed
                if ($model instanceof \App\Models\Step) {
                    $model->update(['status' => 'completed']);
                }
            }

            event(new StepApproved($model, $actor, $nextStage));

            return $model->fresh();
        });
    }

    /**
     * Return a model to a previous stage.
     */
    public function returnStep(
        Model $model,
        User $actor,
        ?int $targetStageId = null,
        ?string $comments = null,
        array $stepFeedbacks = []
    ): Model {
        $this->ensureImplementor($model);

        return DB::transaction(function () use ($model, $actor, $targetStageId, $comments, $stepFeedbacks) {
            $model = $this->lockModel($model);
            $instance = $model->workflowInstance;

            $this->verifyUserCanAct($model, $actor);

            $currentStage = $instance->currentStage;
            if (!$currentStage->can_return) {
                throw new Exception('Return is not allowed at this stage.');
            }

            // Determine target stage
            if ($targetStageId) {
                $prevStage = WorkflowStage::findOrFail($targetStageId);
                // Validate logic for specific stage return
                if ($prevStage->workflow_id !== $instance->workflow_id) {
                    throw new Exception('Target stage must belong to the same workflow.');
                }
                if ($prevStage->order >= $currentStage->order) {
                    throw new Exception('Target stage must be before the current stage.');
                }
            } else {
                $prevStage = $currentStage->previousStage();
            }

            $transition = $this->recordTransition($model, $actor, $currentStage->id, $prevStage?->id, 'return', $comments);

            // Save feedbacks
            if (!empty($stepFeedbacks)) {
                foreach ($stepFeedbacks as $stepId => $note) {
                    $transition->feedbacks()->create([
                        'step_id' => $stepId,
                        'notes' => $note,
                        'created_by' => $actor->id,
                    ]);
                }
            }

            $dueAt = ($prevStage && $prevStage->allowed_days) ? now()->addDays($prevStage->allowed_days) : null;

            $instance->update([
                'current_stage_id' => $prevStage?->id,
                'status' => $prevStage ? 'returned' : 'in_progress', // or 'returned' specific status
                'stage_due_at' => $dueAt,
            ]);

            // Update Step status to 'returned' when returned
            if ($model instanceof \App\Models\Step) {
                $model->update(['status' => 'returned']);
            }

            event(new StepReturned($model, $actor, $prevStage));

            return $model->fresh();
        });
    }

    /**
     * Reject a model (terminates the workflow).
     */
    public function rejectStep(Model $model, User $actor, ?string $comments = null): Model
    {
        $this->ensureImplementor($model);

        return DB::transaction(function () use ($model, $actor, $comments) {
            $model = $this->lockModel($model);
            $instance = $model->workflowInstance;

            $this->verifyUserCanAct($model, $actor);

            $this->recordTransition($model, $actor, $instance->current_stage_id, null, 'reject', $comments);

            $instance->update([
                'current_stage_id' => null,
                'status' => 'rejected',
            ]);

            // Update Step status to 'rejected' when rejected
            if ($model instanceof \App\Models\Step) {
                $model->update(['status' => 'rejected']);
            }

            event(new StepRejected($model, $actor));

            return $model->fresh();
        });
    }

    /**
     * Internal helper to record transitions to keep main logic clean.
     */
    protected function recordTransition(Model $model, User $actor, ?int $fromId, ?int $toId, string $action, ?string $comments)
    {
        return $model->transitions()->create([
            'actor_id' => $actor->id,
            'from_stage_id' => $fromId,
            'to_stage_id' => $toId,
            'action' => $action,
            'comments' => $comments,
        ]);
    }

    protected function ensureImplementor(Model $model): void
    {
        if (!$model instanceof HasWorkflow) {
            throw new InvalidArgumentException('Model must implement HasWorkflow interface.');
        }
    }

    protected function lockModel(Model $model): Model
    {
        return $model->newQuery()->where($model->getKeyName(), $model->getKey())->lockForUpdate()->first();
    }

    protected function verifyUserCanAct(Model $model, User $actor): void
    {
        $instance = $model->workflowInstance;

        if (!$instance || $instance->isTerminal()) {
            throw new Exception('Cannot act on a completed or rejected item.');
        }

        $currentStage = $instance->currentStage;
        if (!$currentStage) {
            throw new Exception('Item has no current stage.');
        }

        $allowed = match ($currentStage->assignment_type) {
            'team' => $actor->workflowTeams()->where('workflow_teams.id', $currentStage->team_id)->exists(),
            'user' => $actor->id === $currentStage->assigned_user_id,
            'role' => $actor->roles()->where('roles.id', $currentStage->assigned_role_id)->exists(),
            default => false,
        };

        if (!$allowed) {
            abort(403, 'You are not authorized to act on this item.');
        }
    }

    public function getPendingStepsForUser(User $user)
    {
        return $user->pendingWorkflowActivities();
    }
}
