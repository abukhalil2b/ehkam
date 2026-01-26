<?php

namespace App\Models;

use App\Contracts\HasWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class AimSectorFeedback extends Model implements HasWorkflow
{
    protected $guarded = [];

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Aim::class);
    }

    public function workflowInstance(): MorphOne
    {
        return $this->morphOne(WorkflowInstance::class, 'workflowable');
    }

    /**
     * Get the user who created this value.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createdby_user_id');
    }

    // ========== WORKFLOW ENGINE RELATIONSHIPS ==========

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function currentStage(): BelongsTo
    {
        return $this->belongsTo(WorkflowStage::class, 'current_stage_id');
    }

    /**
     * All transitions (audit log) for this feedback
     */
    public function transitions(): MorphMany
    {
        return $this->morphMany(WorkflowTransition::class, 'workflowable')->orderBy('created_at', 'desc');
    }

    // ========== WORKFLOW HELPER METHODS ==========

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isInWorkflow(): bool
    {
        return $this->workflow_id !== null && $this->current_stage_id !== null;
    }

    public function isTerminal(): bool
    {
        return in_array($this->status, ['completed', 'rejected']);
    }

    public function canBeActedUpon(): bool
    {
        return !$this->isTerminal() && $this->current_stage_id !== null;
    }

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
