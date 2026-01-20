<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StepFeedback extends Model
{
    protected $table = 'step_feedbacks';

    protected $fillable = [
        'workflow_transition_id',
        'step_id',
        'notes',
        'created_by',
    ];

    /**
     * The transition this feedback belongs to
     */
    public function transition(): BelongsTo
    {
        return $this->belongsTo(WorkflowTransition::class, 'workflow_transition_id');
    }

    /**
     * The step this feedback is for
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(Step::class);
    }

    /**
     * The user who created this feedback
     */
    public function items(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
