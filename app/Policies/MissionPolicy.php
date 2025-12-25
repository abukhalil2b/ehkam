<?php

namespace App\Policies;


use App\Models\User;
use App\Models\Mission;

class MissionPolicy
{
   /**
     * رؤية المشروع
     */
    public function view(User $user, Mission $mission): bool
    {
        return $mission->isLeader($user) || $mission->isMember($user) || $user->id == 1;
    }

    /**
     * تعديل المشروع
     */
    public function update(User $user, Mission $mission): bool
    {
        return $mission->creator_id === $user->id || $mission->isLeader($user);
    }

    /**
     * إدارة الأعضاء
     */
    public function manageMembers(User $user, Mission $mission): bool
    {
        return $mission->isLeader($user);
    }

    /**
     * حذف المشروع
     */
    public function delete(User $user, Mission $mission): bool
    {
        return $mission->creator_id === $user->id;
    }
}
