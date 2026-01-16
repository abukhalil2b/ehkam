<?php

namespace App\Events;

use App\Models\Step;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StepRejected
{
    use Dispatchable, SerializesModels;

    public Step $step;
    public User $actor;

    /**
     * Create a new event instance.
     */
    public function __construct(Step $step, User $actor)
    {
        $this->step = $step;
        $this->actor = $actor;
    }
}
