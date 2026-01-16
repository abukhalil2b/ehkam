<?php

namespace App\Events;

use App\Models\Step;
use App\Models\User;
use App\Models\WorkflowStage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StepReturned
{
    use Dispatchable, SerializesModels;

    public Step $step;
    public User $actor;
    public ?WorkflowStage $targetStage;

    /**
     * Create a new event instance.
     */
    public function __construct(Step $step, User $actor, ?WorkflowStage $targetStage)
    {
        $this->step = $step;
        $this->actor = $actor;
        $this->targetStage = $targetStage;
    }
}
