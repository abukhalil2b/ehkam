<?php

namespace App\Models;

use App\Contracts\HasWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Activity extends Model implements HasWorkflow
{
    protected $guarded = [];

    protected $casts = [
        'is_feed_indicator' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::deleting(function ($activity) {
            // Cleanup associated workflow instance
            $activity->workflowInstance()->delete();
        });
    }

    // ========== EXISTING RELATIONSHIPS ==========

    /**
     * Assessment results for this activity
     */
    public function assessmentResults(): HasMany
    {
        return $this->hasMany(AssessmentResult::class);
    }

    /**
     * The project this activity belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Steps associated with this activity
     */
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    // ========== WORKFLOW ENGINE RELATIONSHIPS ==========

    /**
     * The workflow definition this activity follows
     */
    /**
     * The workflow instance associated with this activity
     */
    public function workflowInstance(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(WorkflowInstance::class, 'workflowable');
    }

    /**
     * The workflow definition this activity follows (via instance)
     */
    /**
     * Helper to get the workflow object directly
     */
    public function getWorkflowAttribute()
    {
        return $this->workflowInstance?->workflow;
    }

    public function getCurrentStageAttribute()
    {
        return $this->workflowInstance?->currentStage;
    }

    /**
     * The user who created this activity
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * All transitions (audit log) for this activity
     */
    public function transitions(): MorphMany
    {
        return $this->morphMany(WorkflowTransition::class, 'workflowable')->orderBy('created_at', 'desc');
    }

    // ========== WORKFLOW HELPER METHODS ==========

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
    /**
     * The employees assigned to evaluate this activity.
     */
    public function employees(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'activity_user_assign')
            ->withTimestamps();
    }
}
