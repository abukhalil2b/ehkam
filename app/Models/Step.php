<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Step extends Model
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
}
