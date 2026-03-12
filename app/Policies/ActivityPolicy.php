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
