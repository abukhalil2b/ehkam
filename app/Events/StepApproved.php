<?php

namespace App\Events;

use App\Models\Step;
use App\Models\User;
use App\Models\WorkflowStage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StepApproved
{
    use Dispatchable, SerializesModels;

    public Step $step;
    public User $actor;
    public ?WorkflowStage $nextStage;

    /**
     * Create a new event instance.
     */
    public function __construct(Step $step, User $actor, ?WorkflowStage $nextStage)
    {
        $this->step = $step;
        $this->actor = $actor;
        $this->nextStage = $nextStage;
    }
}
