<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_projects') || $user->id === 1;
    }

    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        // Terminal states cannot be updated
        if ($project->isTerminal()) {
            return false;
        }

        // If they have approval rights, they can update/manage it
        if ($user->hasPermission('approve_projects') || $user->id === 1) {
            return true;
        }

        // Otherwise, only if they have submit_projects and it's a draft or returned.
        // Also they should ideally be the creator, but for simplicity of this demo:
        return $user->hasPermission('submit_projects') && in_array($project->status, ['draft', 'returned']);
    }

    /**
     * Determine whether the user can submit the project.
     */
    public function submit(User $user, Project $project): bool
    {
        return in_array($project->status, ['draft', 'returned']) && 
               ($user->hasPermission('submit_projects') || $user->id === 1);
    }

    /**
     * Determine whether the user can approve, return, or reject the project.
     */
    public function approve(User $user, Project $project): bool
    {
        return $project->status === 'submitted' && 
               ($user->hasPermission('approve_projects') || $user->id === 1);
    }
}
