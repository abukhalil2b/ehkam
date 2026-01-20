<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Stages in this workflow, ordered by their order field
     */
    public function stages()
    {
        return $this->hasMany(WorkflowStage::class)->orderBy('order');
    }

    /**
     * Activities following this workflow
     * @deprecated Use instances() instead
     */
    // public function activities() { ... }

    /**
     * Get the first stage of this workflow
     */
    public function firstStage()
    {
        return $this->stages()->orderBy('order')->first();
    }

    /**
     * Get the last stage of this workflow
     */
    public function lastStage()
    {
        return $this->stages()->orderBy('order', 'desc')->first();
    }

    /**
     * Reindex stages to gapped integers (10, 20, 30...)
     * Use when gaps are exhausted after many insertions
     */
    public function reindexStages()
    {
        $this->stages()->orderBy('order')->get()->each(function ($stage, $index) {
            $stage->update(['order' => ($index + 1) * 10]);
        });
    }

    /**
     * Check if workflow is in use (has associated instances)
     */
    public function isUsed(): bool
    {
        return $this->instances()->exists();
    }

    /**
     * Check if workflow can be deleted (no active instances)
     */
    public function canBeDeleted(): bool
    {
        return !$this->instances()
            ->whereNotIn('status', ['completed', 'rejected'])
            ->exists();
    }

    /**
     * Get instances of this workflow
     */
    public function instances()
    {
        return $this->hasMany(WorkflowInstance::class);
    }
}
