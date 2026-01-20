<?php

namespace App\Events;

use App\Models\Step;
use App\Models\User;
use App\Models\WorkflowStage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Database\Eloquent\Model;

class StepReturned
{
    use Dispatchable, SerializesModels;

    public Model $model;
    public $step; // Backward compatibility alias
    public User $actor;
    public ?WorkflowStage $targetStage;

    /**
     * Create a new event instance.
     */
    public function __construct(Model $model, User $actor, ?WorkflowStage $targetStage)
    {
        $this->model = $model;
        $this->step = $model; // Alias
        $this->actor = $actor;
        $this->targetStage = $targetStage;
    }
}
