<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StepTransition extends Model
{
    protected $guarded = [];

    /**
     * The step this transition belongs to
     */
    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    /**
     * The user who performed this action
     */
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * The stage the step moved from
     */
    public function fromStage()
    {
        return $this->belongsTo(WorkflowStage::class, 'from_stage_id');
    }

    /**
     * The stage the step moved to
     */
    public function toStage()
    {
        return $this->belongsTo(WorkflowStage::class, 'to_stage_id');
    }

    /**
     * Get human-readable action name in Arabic
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'submit' => 'إرسال',
            'approve' => 'موافقة',
            'return' => 'إعادة',
            'reject' => 'رفض',
            default => $this->action,
        };
    }
}
