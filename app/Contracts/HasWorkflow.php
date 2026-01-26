<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\WorkflowInstance;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasWorkflow
{
    /**
     * Get the workflow instance for this model
     */
    public function workflowInstance(): MorphOne;

    /**
     * Get all transitions (audit log) for this model
     */
    public function transitions(): MorphMany;

    public function isInWorkflow(): bool;

    public function canBeActedUpon(): bool;

    public function isTerminal(): bool;

    public function isDraft(): bool;
}
