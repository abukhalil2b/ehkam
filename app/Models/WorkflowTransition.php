<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WorkflowTransition extends Model
{
    protected $guarded = [];

    /**
     * Get the parent workflowable model (Step, AimSectorFeedback, etc.)
     */
    public function workflowable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who performed this transition
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Get the stage transitioned from
     */
    public function fromStage(): BelongsTo
    {
        return $this->belongsTo(WorkflowStage::class, 'from_stage_id');
    }

    /**
     * Get the stage transitioned to
     */
    public function toStage(): BelongsTo
    {
        return $this->belongsTo(WorkflowStage::class, 'to_stage_id');
    }

    /**
     * Get human-readable action label in Arabic
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'submit' => 'تم الإرسال',
            'approve' => 'تمت الموافقة',
            'return' => 'تم الإرجاع',
            'reject' => 'تم الرفض',
            default => $this->action,
        };
    }
}
