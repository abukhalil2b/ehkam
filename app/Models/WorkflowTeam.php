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
     * Get all pending workflow items for this team.
     * This includes all entities (Steps, Activities, AppointmentRequests, etc.)
     * that are currently in a workflow stage assigned to this team.
     * 
     * @return \Illuminate\Support\Collection Collection of workflow items with their types
     */
    public function pendingItems()
    {
        // Get all workflow instances where current stage is assigned to this team
        $instances = \App\Models\WorkflowInstance::whereHas('currentStage', function ($query) {
            $query->where('team_id', $this->id);
        })
        ->whereNotIn('status', ['completed', 'draft', 'rejected'])
        ->with(['workflowable', 'currentStage'])
        ->get();

        // Group by entity type and load the actual models
        $items = collect();
        
        foreach ($instances as $instance) {
            if ($instance->workflowable) {
                $items->push([
                    'instance' => $instance,
                    'item' => $instance->workflowable,
                    'type' => class_basename($instance->workflowable_type),
                    'type_label' => $this->getEntityTypeLabel($instance->workflowable_type),
                ]);
            }
        }

        return $items;
    }

    /**
     * Get human-readable label for entity type
     */
    protected function getEntityTypeLabel(string $entityType): string
    {
        return match ($entityType) {
            'App\Models\Step' => 'خطوة',
            'App\Models\Activity' => 'نشاط',
            'App\Models\AppointmentRequest' => 'طلب موعد',
            default => class_basename($entityType),
        };
    }

    /**
     * Get pending activities for this team (for backward compatibility)
     * @deprecated Use pendingItems() instead
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
