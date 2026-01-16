<?php

namespace App\Listeners;

use App\Events\StepSubmitted;
use App\Events\StepApproved;
use App\Events\StepReturned;
use App\Events\StepRejected;
use App\Notifications\WorkflowStepNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyTeamMembers implements ShouldQueue
{
    /**
     * Handle step submitted event - notify first stage team
     */
    public function handleStepSubmitted(StepSubmitted $event): void
    {
        $this->notifyTeamMembers(
            $event->targetStage,
            $event->step,
            $event->actor,
            'submitted',
            'تم إرسال خطوة جديدة إلى فريقك'
        );
    }

    /**
     * Handle step approved event - notify next stage team
     */
    public function handleStepApproved(StepApproved $event): void
    {
        if ($event->nextStage) {
            $this->notifyTeamMembers(
                $event->nextStage,
                $event->step,
                $event->actor,
                'approved',
                'تمت الموافقة على خطوة وإرسالها إلى فريقك'
            );
        } else {
            // Workflow completed - notify creator
            $this->notifyCreator($event->step, $event->actor, 'completed');
        }
    }

    /**
     * Handle step returned event - notify target stage team
     */
    public function handleStepReturned(StepReturned $event): void
    {
        if ($event->targetStage) {
            $this->notifyTeamMembers(
                $event->targetStage,
                $event->step,
                $event->actor,
                'returned',
                'تمت إعادة خطوة إلى فريقك'
            );
        }
    }

    /**
     * Handle step rejected event - notify creator
     */
    public function handleStepRejected(StepRejected $event): void
    {
        $this->notifyCreator($event->step, $event->actor, 'rejected');
    }

    /**
     * Notify all members of a team about a workflow step
     */
    protected function notifyTeamMembers(
        $stage,
        $step,
        $actor,
        string $action,
        string $message
    ): void {
        $team = $stage->team;

        if (!$team) {
            return;
        }

        foreach ($team->users as $user) {
            // Don't notify the actor who performed the action
            if ($user->id === $actor->id) {
                continue;
            }

            $user->notify(new WorkflowStepNotification(
                $step,
                $action,
                $message,
                $actor
            ));
        }
    }

    /**
     * Notify the step creator
     */
    protected function notifyCreator($step, $actor, string $action): void
    {
        if (!$step->creator_id || $step->creator_id === $actor->id) {
            return;
        }

        $message = match ($action) {
            'completed' => 'تم إكمال سير العمل للخطوة الخاصة بك',
            'rejected' => 'تم رفض الخطوة الخاصة بك',
            default => 'تم تحديث حالة الخطوة الخاصة بك',
        };

        $step->creator->notify(new WorkflowStepNotification(
            $step,
            $action,
            $message,
            $actor
        ));
    }
}
