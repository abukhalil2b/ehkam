<?php

namespace App\Models;

use App\Contracts\HasWorkflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;


class Step extends Model implements HasWorkflow
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_need_evidence_file' => 'boolean',
        'is_need_to_put_target' => 'boolean',
    ];


    // ========== RELATIONSHIPS ==========

    /**
     * The activity this step belongs to
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * The project this step belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Workflow requirements (evidence files) attached to workflow transitions
     */
    public function workflowRequirements(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            WorkflowRequirement::class,
            WorkflowTransition::class,
            'workflowable_id', // Foreign key on workflow_transitions
            'workflow_transition_id', // Foreign key on workflow_requirements
            'id', // Local key on steps
            'id' // Local key on workflow_transitions
        )->where('workflow_transitions.workflowable_type', Step::class);
    }

    /**
     * Organizational unit tasks
     */
    public function StepOrgUnitTasks(): HasMany
    {
        return $this->hasMany(StepOrgUnitTask::class, 'step_id');
    }

    /**
     * Feedback notes associated with this step from workflow returns
     */
    public function feedbacks(): HasMany
    {
        return $this->hasMany(StepFeedback::class);
    }

    // ========== INTERFACE METHODS ==========
    // ========== WORKFLOW HELPER METHODS ==========
    public function workflowInstance(): MorphOne
    {
        return $this->morphOne(WorkflowInstance::class, 'workflowable');
    }

    /**
     * All transitions (audit log) for this activity
     */
    public function transitions(): MorphMany
    {
        return $this->morphMany(WorkflowTransition::class, 'workflowable')->orderBy('created_at', 'desc');
    }
    /**
     * Check if activity is in a terminal state (completed or rejected)
     */
    public function isTerminal(): bool
    {
        return $this->workflowInstance ? $this->workflowInstance->isTerminal() : false;
    }

    /**
     * Check if activity is a draft (not yet submitted to workflow)
     */
    public function isDraft(): bool
    {
        return $this->workflowInstance ? $this->workflowInstance->isDraft() : true; // Default to draft if no instance
    }

    /**
     * Check if activity is actively in a workflow
     */
    public function isInWorkflow(): bool
    {
        return $this->workflowInstance !== null && $this->workflowInstance->workflow_id !== null;
    }

    /**
     * Check if activity can be acted upon (not terminal, has current stage)
     */
    public function canBeActedUpon(): bool
    {
        return $this->workflowInstance && !$this->workflowInstance->isTerminal() && $this->workflowInstance->current_stage_id !== null;
    }

    /**
     * Get human-readable status label in Arabic
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->workflowInstance ? $this->workflowInstance->status_label : 'مسودة';
    }

    /**
     * Check if activity is delayed based on escalation level
     */
    public function isDelayed(): bool
    {
        return $this->workflowInstance ? $this->workflowInstance->escalation_level > 0 : false;
    }

    /**
     * Get escalation status in Arabic
     */
    public function getEscalationStatusAttribute(): string
    {
        $level = $this->workflowInstance?->escalation_level ?? 0;
        return match ($level) {
            0 => 'عادي',
            1 => 'تحذير',
            2 => 'تصعيد',
            default => 'غير معروف',
        };
    }

    /**
     * Check if activity is overdue (stage deadline passed)
     */
    public function isOverdue(): bool
    {
        $due = $this->workflowInstance?->stage_due_at;
        return $due && now()->gt($due);
    }
}
