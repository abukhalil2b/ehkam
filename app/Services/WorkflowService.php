<?php

namespace App\Services;

use App\Models\Step;
use App\Models\StepTransition;
use App\Models\User;
use App\Models\WorkflowStage;
use App\Events\StepSubmitted;
use App\Events\StepApproved;
use App\Events\StepReturned;
use App\Events\StepRejected;
use Illuminate\Support\Facades\DB;

class WorkflowService
{
    /**
     * Submit a step to its workflow (moves from draft to first stage)
     *
     * @param Step $step
     * @param User $actor
     * @param string|null $comments
     * @return Step
     * @throws \Exception
     */
    public function submitStep(Step $step, User $actor, ?string $comments = null): Step
    {
        return DB::transaction(function () use ($step, $actor, $comments) {
            // Lock the step row to prevent concurrent transitions
            $step = Step::where('id', $step->id)->lockForUpdate()->first();

            if (!$step->workflow_id) {
                throw new \Exception('Step must be assigned to a workflow before submission.');
            }

            if (!$step->isDraft()) {
                throw new \Exception('Only draft steps can be submitted.');
            }

            $firstStage = $step->workflow->firstStage();

            if (!$firstStage) {
                throw new \Exception('Workflow has no stages defined.');
            }

            // Record the transition
            StepTransition::create([
                'step_id' => $step->id,
                'actor_id' => $actor->id,
                'from_stage_id' => null,
                'to_stage_id' => $firstStage->id,
                'action' => 'submit',
                'comments' => $comments,
            ]);

            // Move to first stage
            $step->update([
                'current_stage_id' => $firstStage->id,
                'status' => 'in_progress',
            ]);

            // Dispatch event for notifications
            event(new StepSubmitted($step, $actor, $firstStage));

            return $step->fresh();
        });
    }

    /**
     * Approve a step (moves to next stage or completes the workflow)
     *
     * @param Step $step
     * @param User $actor
     * @param string|null $comments
     * @return Step
     * @throws \Exception
     */
    public function approveStep(Step $step, User $actor, ?string $comments = null): Step
    {
        return DB::transaction(function () use ($step, $actor, $comments) {
            // Lock the step row to prevent concurrent transitions
            $step = Step::where('id', $step->id)->lockForUpdate()->first();

            $this->verifyUserCanAct($step, $actor);

            $currentStage = $step->currentStage;

            if (!$currentStage->can_approve) {
                throw new \Exception('Approval is not allowed at this stage.');
            }

            // Find the next stage
            $nextStage = $currentStage->nextStage();

            // Record the transition
            StepTransition::create([
                'step_id' => $step->id,
                'actor_id' => $actor->id,
                'from_stage_id' => $currentStage->id,
                'to_stage_id' => $nextStage?->id,
                'action' => 'approve',
                'comments' => $comments,
            ]);

            if ($nextStage) {
                // Move to next stage
                $step->update([
                    'current_stage_id' => $nextStage->id,
                    'status' => 'in_progress',
                ]);
            } else {
                // No next stage - workflow is complete
                $step->update([
                    'current_stage_id' => null,
                    'status' => 'completed',
                ]);
            }

            // Dispatch event for notifications
            event(new StepApproved($step, $actor, $nextStage));

            return $step->fresh();
        });
    }

    /**
     * Return a step to a previous stage (or a specific stage)
     *
     * @param Step $step
     * @param User $actor
     * @param int|null $targetStageId Specific stage to return to (null = previous stage)
     * @param string|null $comments
     * @return Step
     * @throws \Exception
     */
    public function returnStep(
        Step $step,
        User $actor,
        ?int $targetStageId = null,
        ?string $comments = null
    ): Step {
        return DB::transaction(function () use ($step, $actor, $targetStageId, $comments) {
            // Lock the step row to prevent concurrent transitions
            $step = Step::where('id', $step->id)->lockForUpdate()->first();

            $this->verifyUserCanAct($step, $actor);

            $currentStage = $step->currentStage;

            if (!$currentStage->can_return) {
                throw new \Exception('Return is not allowed at this stage.');
            }

            // Determine target stage
            if ($targetStageId) {
                $prevStage = WorkflowStage::find($targetStageId);

                if (!$prevStage) {
                    throw new \Exception('Target stage not found.');
                }

                if ($prevStage->workflow_id !== $step->workflow_id) {
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
            StepTransition::create([
                'step_id' => $step->id,
                'actor_id' => $actor->id,
                'from_stage_id' => $currentStage->id,
                'to_stage_id' => $prevStage?->id,
                'action' => 'return',
                'comments' => $comments,
            ]);

            // Move to previous stage
            $step->update([
                'current_stage_id' => $prevStage?->id,
                'status' => $prevStage ? 'returned' : 'in_progress',
            ]);

            // Dispatch event for notifications
            event(new StepReturned($step, $actor, $prevStage));

            return $step->fresh();
        });
    }

    /**
     * Reject a step (terminates the workflow)
     *
     * @param Step $step
     * @param User $actor
     * @param string|null $comments
     * @return Step
     * @throws \Exception
     */
    public function rejectStep(Step $step, User $actor, ?string $comments = null): Step
    {
        return DB::transaction(function () use ($step, $actor, $comments) {
            // Lock the step row to prevent concurrent transitions
            $step = Step::where('id', $step->id)->lockForUpdate()->first();

            $this->verifyUserCanAct($step, $actor);

            // Record the transition
            StepTransition::create([
                'step_id' => $step->id,
                'actor_id' => $actor->id,
                'from_stage_id' => $step->current_stage_id,
                'to_stage_id' => null,
                'action' => 'reject',
                'comments' => $comments,
            ]);

            // Mark as rejected (terminal state)
            $step->update([
                'current_stage_id' => null,
                'status' => 'rejected',
            ]);

            // Dispatch event for notifications
            event(new StepRejected($step, $actor));

            return $step->fresh();
        });
    }

    /**
     * Verify the user has permission to act on the step
     *
     * @param Step $step
     * @param User $actor
     * @throws \Exception
     */
    protected function verifyUserCanAct(Step $step, User $actor): void
    {
        if ($step->isTerminal()) {
            throw new \Exception('Cannot act on a completed or rejected step.');
        }

        if (!$step->current_stage_id) {
            throw new \Exception('Step has no current stage.');
        }

        $teamId = $step->currentStage->team_id;
        $userInTeam = $actor->workflowTeams()->where('workflow_teams.id', $teamId)->exists();

        if (!$userInTeam) {
            abort(403, 'You are not authorized to act on this step.');
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
        return $user->pendingWorkflowSteps();
    }

    /**
     * Assign a step to a workflow and optionally submit it
     *
     * @param Step $step
     * @param int $workflowId
     * @param User $actor
     * @param bool $autoSubmit
     * @return Step
     */
    public function assignWorkflow(Step $step, int $workflowId, User $actor, bool $autoSubmit = false): Step
    {
        $step->update(['workflow_id' => $workflowId]);

        if ($autoSubmit) {
            return $this->submitStep($step->fresh(), $actor);
        }

        return $step->fresh();
    }
}
