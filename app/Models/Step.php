<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
        'due_date' => 'date',
    ];

    // ========== EXISTING RELATIONSHIPS ==========

    public function stepEvidenceFiles()
    {
        return $this->hasMany(StepEvidenceFile::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function StepOrgUnitTasks()
    {
        return $this->hasMany(StepOrgUnitTask::class, 'step_id');
    }

    /**
     * Legacy workflow relationship (kept for backward compatibility)
     */
    public function currentWorkflow()
    {
        return $this->hasOne(StepWorkflow::class)->latestOfMany();
    }

    // ========== NEW WORKFLOW ENGINE RELATIONSHIPS ==========

    /**
     * The workflow definition this step follows
     */
    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * The current stage in the workflow
     */
    public function currentStage()
    {
        return $this->belongsTo(WorkflowStage::class, 'current_stage_id');
    }

    /**
     * The user who created this step
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * The user assigned to this step
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * All transitions (audit log) for this step
     */
    public function transitions()
    {
        return $this->hasMany(StepTransition::class)->orderBy('created_at', 'desc');
    }

    // ========== WORKFLOW HELPER METHODS ==========

    /**
     * Check if step is in a terminal state (completed or rejected)
     */
    public function isTerminal(): bool
    {
        return in_array($this->status, ['completed', 'rejected']);
    }

    /**
     * Check if step is a draft (not yet submitted to workflow)
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if step is actively in a workflow
     */
    public function isInWorkflow(): bool
    {
        return $this->workflow_id !== null && $this->current_stage_id !== null;
    }

    /**
     * Check if step can be acted upon (not terminal, has current stage)
     */
    public function canBeActedUpon(): bool
    {
        return !$this->isTerminal() && $this->current_stage_id !== null;
    }

    /**
     * Get human-readable status label in Arabic
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'مسودة',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'returned' => 'معاد',
            'rejected' => 'مرفوض',
            default => $this->status,
        };
    }
}
