<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WorkflowInstance extends Model
{
    protected $guarded = [];

    protected $casts = [
        'stage_due_at' => 'datetime',
    ];

    /**
     * The parent model (Activity, Project, etc.)
     */
    public function workflowable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The workflow definition being followed
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * The current stage
     */
    public function currentStage(): BelongsTo
    {
        return $this->belongsTo(WorkflowStage::class, 'current_stage_id');
    }

    /**
     * The user who started this instance
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // ========== HELPER METHODS ==========

    /**
     * Check if instance is in a terminal state
     */
    public function isTerminal(): bool
    {
        return in_array($this->status, ['completed', 'rejected']);
    }

    /**
     * Check if instance is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
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
            'delayed' => 'متأخر',
            default => $this->status,
        };
    }
}
