<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowStage extends Model
{
    protected $guarded = [];

    protected $casts = [
        'can_approve' => 'boolean',
        'can_return' => 'boolean',
        'meta' => 'array',
    ];

    /**
     * The workflow this stage belongs to
     */
    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * The team assigned to this stage
     */
    public function team()
    {
        return $this->belongsTo(WorkflowTeam::class, 'team_id');
    }

    /**
     * Items currently at this stage
     */
    public function instances()
    {
        return $this->hasMany(WorkflowInstance::class, 'current_stage_id');
    }

    /**
     * Get the next stage in the workflow
     */
    public function nextStage()
    {
        return WorkflowStage::where('workflow_id', $this->workflow_id)
            ->where('order', '>', $this->order)
            ->orderBy('order', 'asc')
            ->first();
    }

    /**
     * Get the previous stage in the workflow
     */
    public function previousStage()
    {
        return WorkflowStage::where('workflow_id', $this->workflow_id)
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();
    }

    /**
     * Check if this is the first stage
     */
    public function isFirstStage(): bool
    {
        return !$this->previousStage();
    }

    /**
     * Check if this is the last stage
     */
    public function isLastStage(): bool
    {
        return !$this->nextStage();
    }

    /**
     * Check if stage can be deleted (no items currently at this stage)
     */
    public function canBeDeleted(): bool
    {
        return $this->instances()->count() === 0;
    }
}
