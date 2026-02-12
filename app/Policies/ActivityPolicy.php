<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the activity.
     */
    public function view(User $user, Activity $activity)
    {
        // 1. Admin/Manager with global permission
        if ($user->hasPermission('activity.view')) {
            return true;
        }

        // 2. Creator of the activity
        if ($user->id === $activity->creator_id) {
            return true;
        }

        // 3. Workflow Participant (Current Actor)
        if ($user->canActOnActivity($activity)) {
            return true;
        }

        // 4. Determine if user was PREVIOUSLY involved (History)
        // This query might be expensive, so use caching or simple check if needed.
        // For now, let's allow if they are in any team that is part of the workflow?
        // Or strictly: have they ever acted on it?
        /*
        $hasActed = $activity->transitions()
            ->where('actor_id', $user->id)
            ->exists();
        if ($hasActed) return true;
        */

        return false;
    }

    /**
     * Determine whether the user can create activities.
     */
    public function create(User $user)
    {
        return $user->hasPermission('activity.create');
    }

    /**
     * Determine whether the user can update the activity.
     */
    public function update(User $user, Activity $activity)
    {
        if ($user->hasPermission('activity.edit')) {
            return true;
        }

        return false;
    }
}
