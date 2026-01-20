<?php

namespace App\Events;

use App\Models\Step;
use App\Models\User;
use App\Models\WorkflowStage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Database\Eloquent\Model;

class StepApproved
{
    use Dispatchable, SerializesModels;

    public Model $model;
    public $step; // Backward compatibility alias
    public User $actor;
    public ?WorkflowStage $nextStage;

    /**
     * Create a new event instance.
     */
    public function __construct(Model $model, User $actor, ?WorkflowStage $nextStage)
    {
        $this->model = $model;
        $this->step = $model; // Alias
        $this->actor = $actor;
        $this->nextStage = $nextStage;
    }
}
