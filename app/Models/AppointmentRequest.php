<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\HasWorkflow;
use App\Traits\Workflowable;
use App\Models\WorkflowInstance;
use App\Models\WorkflowTransition;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AppointmentRequest extends Model implements HasWorkflow
{
    use Workflowable;

    protected $guarded = [];

    public function workflowInstance(): MorphOne
    {
        return $this->morphOne(WorkflowInstance::class, 'workflowable');
    }
    public function transitions(): MorphMany
    {
        return $this->morphMany(WorkflowTransition::class, 'workflowable');
    }

    public function slotProposals()
    {
        return $this->hasMany(CalendarSlotProposal::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function minister()
    {
        return $this->belongsTo(User::class, 'minister_id');
    }

    public function scopePendingForUser($query, User $user)
    {
        return $query->whereHas('workflowInstance', function ($q) use ($user) {
            $q->whereIn('status', ['in_progress', 'returned'])
                ->whereHas('currentStage', function ($q2) use ($user) {
                    $q2->whereHas('team.users', function ($q3) use ($user) {
                        $q3->where('users.id', $user->id);
                    });
                });
        });
    }

    public function isApproved(): bool
    {
        return $this->workflowInstance && $this->workflowInstance->status === 'completed';
    }

    // HasWorkflow interface methods
    public function isInWorkflow(): bool
    {
        return $this->workflowInstance && in_array($this->workflowInstance->status, ['in_progress', 'returned']);
    }

    public function canBeActedUpon(): bool
    {
        return $this->workflowInstance && !$this->isTerminal() && $this->workflowInstance->current_stage_id !== null;
    }

    public function isTerminal(): bool
    {
        return $this->workflowInstance && in_array($this->workflowInstance->status, ['completed', 'rejected']);
    }

    public function isDraft(): bool
    {
        return $this->workflowInstance && $this->workflowInstance->status === 'draft';
    }
}
