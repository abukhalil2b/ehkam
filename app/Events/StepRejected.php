<?php

namespace App\Events;

use App\Models\Step;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Database\Eloquent\Model;

class StepRejected
{
    use Dispatchable, SerializesModels;

    public Model $model;
    public $step; // Backward compatibility
    public User $actor;

    /**
     * Create a new event instance.
     */
    public function __construct(Model $model, User $actor)
    {
        $this->model = $model;
        $this->step = $model; // Alias
        $this->actor = $actor;
    }
}
