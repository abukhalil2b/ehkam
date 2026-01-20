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
     * Get all steps currently pending for this team.
     * Note: Steps don't have workflows - only Activities do.
     */
    /**
     * Get all steps currently pending for this team.
     * Note: Steps don't have workflows - only Activities do.
     * @deprecated Use pendingActivities() instead
     */
    public function pendingSteps()
    {
        // Steps don't have workflow relationships
        // Return empty query builder for compatibility
        return Step::whereRaw('1 = 0');
    }

    /**
     * Get pending activities for this team
     */
    public function pendingActivities()
    {
        return Activity::whereHas('workflowInstance', function ($q) {
            $q->whereHas('currentStage', function ($query) {
                $query->where('team_id', $this->id);
            })->whereNotIn('status', ['completed', 'draft', 'rejected']);
        });
    }
}
