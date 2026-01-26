<?php

namespace App\Traits;

use App\Contracts\HasWorkflow;
use App\Models\WorkflowInstance;
use App\Models\WorkflowTransition;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Workflowable
{
    /**
     * Get the workflow instance for this model
     */
    public function workflowInstance(): MorphOne
    {
        return $this->morphOne(WorkflowInstance::class, 'workflowable');
    }

    /**
     * Get the workflow definition via instance
     */
    public function workflow()
    {
        return $this->workflowInstance()->with('workflow')->first()?->workflow();
    }

    /**
     * Get all workflow transitions (audit log)
     */
    public function transitions(): MorphMany
    {
        return $this->morphMany(WorkflowTransition::class, 'workflowable');
    }

    /**
     * Check if model is actively in a workflow
     */
    public function isInWorkflow(): bool
    {
        return $this->workflowInstance && !$this->isTerminal();
    }

    /**
     * Check if model can be acted upon (not terminal, has current stage)
     */
    public function canBeActedUpon(): bool
    {
        $instance = $this->workflowInstance;
        return $instance && !$instance->isTerminal() && $instance->currentStage !== null;
    }

    /**
     * Check if model is in a terminal state (completed or rejected)
     */
    public function isTerminal(): bool
    {
        $instance = $this->workflowInstance;
        return $instance && in_array($instance->status, ['completed', 'rejected']);
    }

    /**
     * Check if model is a draft (not yet submitted to workflow)
     */
    public function isDraft(): bool
    {
        $instance = $this->workflowInstance;
        return $instance && $instance->status === 'draft';
    }
}
