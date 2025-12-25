<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Models\Mission;

class TaskPolicy
{
    /**
     * رؤية المهمة
     */
    public function view(User $user, Task $task): bool
    {
        return $task->canBeViewedBy($user);
    }

    /**
     * رؤية جميع المهام في المشروع
     */
    public function viewAny(User $user, Mission $mission): bool
    {
        // المسؤول يرى كل شيء
        if ($mission->isLeader($user)) {
            return true;
        }

        // الأعضاء الذين لديهم صلاحية رؤية كل المهام
        $member = $mission->members()->where('user_id', $user->id)->first();
        return $member && $member->can_view_all_tasks;
    }

    /**
     * إنشاء مهمة
     */
    public function create(User $user, Mission $mission): bool
    {
        // المسؤول يمكنه الإنشاء دائماً
        if ($mission->isLeader($user)) {
            return true;
        }

        // الأعضاء الذين لديهم صلاحية إنشاء المهام
        $member = $mission->members()->where('user_id', $user->id)->first();
        return $member && $member->can_create_tasks;
    }

    /**
     * تعديل المهمة
     */
    public function update(User $user, Task $task): bool
    {
        return $task->canBeEditedBy($user);
    }

    /**
     * حذف المهمة
     */
    public function delete(User $user, Task $task): bool
    {
        // المسؤول أو منشئ المهمة
        return $task->mission->isLeader($user) || $task->creator_id === $user->id;
    }

    /**
     * تخصيص المهمة لشخص
     */
    public function assign(User $user, Task $task): bool
    {
        // المسؤول أو منشئ المهمة
        return $task->mission->isLeader($user) || $task->creator_id === $user->id;
    }

    /**
     * تغيير حالة المهمة
     */
    public function updateStatus(User $user, Task $task): bool
    {
        // المسؤول، منشئ المهمة، أو المخصص له
        return $task->mission->isLeader($user)
            || $task->creator_id === $user->id
            || $task->assigned_to === $user->id;
    }
}
