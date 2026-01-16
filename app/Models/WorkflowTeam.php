<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowTeam extends Model
{
    protected $guarded = [];

    /**
     * Users belonging to this team
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_workflow_team')
            ->withTimestamps();
    }

    /**
     * Stages assigned to this team
     */
    public function stages()
    {
        return $this->hasMany(WorkflowStage::class, 'team_id');
    }

    /**
     * Get pending steps for this team
     */
    public function pendingSteps()
    {
        return Step::whereHas('currentStage', function ($query) {
            $query->where('team_id', $this->id);
        })->where(function ($query) {
            $query->whereNotIn('status', ['completed', 'draft', 'rejected']);
        });
    }
}
