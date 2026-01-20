<?php

namespace App\Contracts;

use App\Models\Workflow;
use App\Models\WorkflowStage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Interface for models that can participate in workflows
 */
interface HasWorkflow
{
    /**
     * Get the workflow definition this model follows
     */
    /**
     * Get all transitions (audit log) for this model
     */
    public function transitions(): MorphMany;

    /**
     * Check if model is actively in a workflow
     */
    public function isInWorkflow(): bool;

    /**
     * Check if model can be acted upon (not terminal, has current stage)
     */
    public function canBeActedUpon(): bool;

    /**
     * Check if model is in a terminal state (completed or rejected)
     */
    public function isTerminal(): bool;

    /**
     * Check if model is a draft (not yet submitted to workflow)
     */
    public function isDraft(): bool;
}
