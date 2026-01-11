<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Profile;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected ?array $cachedPermissions = null;

    // ========== Calendar ==========
    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class, 'target_user_id');
    }

    public function createdEvents()
    {
        return $this->hasMany(CalendarEvent::class, 'user_id');
    }

    public function delegationsGranted()
    {
        return $this->hasMany(CalendarDelegation::class, 'manager_id');
    }

    public function delegationsReceived()
    {
        return $this->hasMany(CalendarDelegation::class, 'employee_id');
    }

    // ========== Profile ==========
    public function assignProfile($profile)
    {
        $profileId = $profile instanceof Profile ? $profile->id : $profile;
        $this->profiles()->syncWithoutDetaching($profileId);
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class);
    }

    public function profiles()
    {
        // Use the default naming conventions or specify the custom pivot table 'user_profile'
        return $this->belongsToMany(Profile::class, 'user_profile', 'user_id', 'profile_id')
            ->withTimestamps(); // Include timestamps from the pivot table
    }

    /**
     * Remove a profile from the user.
     * @param Profile|int $profile
     */
    public function revokeProfile($profile)
    {
        $profileId = $profile instanceof Profile ? $profile->id : $profile;
        $this->profiles()->detach($profileId);
    }

    // ========== Permission ==========
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permission', 'user_id', 'permission_id');
    }

    /**
     * Sync direct permissions for the user.
     * @param array $permissions Array of Permission IDs
     */
    public function syncPermissions(array $permissions)
    {
        $this->permissions()->sync($permissions);
    }

    public function hasPermission(string $slug): bool
    {
        // 1. Super Admin Bypass (Crucial for maintenance)
        if ($this->id === 1) {
            return true;
        }

        // 2. Check if the required slug is in the user's permission list
        return in_array($slug, $this->getPermissions());
    }

    // Assuming this method is inside a User Model or similar class
    public function getPermissions()
    {
        // Check for Active Profile in Session
        $activeProfileId = session('active_profile_id');

        // 1. Get the IDs of profiles to check (Active One or All)
        if ($activeProfileId) {
            // Validate that the user actually owns this profile
            $hasProfile = DB::table('user_profile')
                ->where('user_id', $this->id)
                ->where('profile_id', $activeProfileId)
                ->exists();

            $profileIds = $hasProfile ? [$activeProfileId] : [];
        } else {
            // Default: All profiles
            $profileIds = DB::table('user_profile')
                ->where('user_id', $this->id)
                ->pluck('profile_id')
                ->toArray();
        }

        // Initialize an array to store all unique permission slugs.
        $allPermissionsSlugs = [];

        // 2. Get the slugs for all permissions associated with the selected profile(s).
        if (!empty($profileIds)) {
            $profilePermissionsSlugs = DB::table('profile_permission')
                ->join('permissions', 'profile_permission.permission_id', '=', 'permissions.id')
                ->whereIn('profile_permission.profile_id', $profileIds)
                ->pluck('permissions.slug')
                ->toArray();

            $allPermissionsSlugs = array_merge($allPermissionsSlugs, $profilePermissionsSlugs);
        }

        // 3. Get the slugs for all permissions directly (individually) assigned to the user.
        // NOTE: We assume individual permissions are global and apply regardless of profile context.
        $individualPermissionsSlugs = DB::table('user_permission')
            ->join('permissions', 'user_permission.permission_id', '=', 'permissions.id')
            ->where('user_permission.user_id', $this->id)
            ->pluck('permissions.slug')
            ->toArray();

        $allPermissionsSlugs = array_merge($allPermissionsSlugs, $individualPermissionsSlugs);

        // 4. Ensure all permissions are unique and re-index the array.
        return array_values(array_unique($allPermissionsSlugs));
    }

    public function getActiveProfile()
    {
        $id = session('active_profile_id');
        if (!$id)
            return null;
        return $this->profiles()->where('profiles.id', $id)->first();
    }

    // 1. Relationship to all historical assignments
    public function positionHistory()
    {
        return $this->hasMany(EmployeeAssignment::class);
    }

    // 2. Relationship to the CURRENT (active) assignment history record
    public function currentHistory()
    {
        return $this->hasOne(EmployeeAssignment::class)
            ->whereNull('end_date') // A null end_date signifies the current, active assignment
            ->latest('start_date'); // Ensures we get the most recent active one if multiple somehow exist
    }

    // Current active position
    public function currentPosition()
    {
        return $this->hasOne(EmployeeAssignment::class)
            ->whereNull('end_date')
            ->latest('start_date') // in case multiple active (defensive)
            ->with('position')     // eager load position relation
            ->first()?->position;  // return Position instance
    }

    // Current active organizational unit
    public function currentUnit()
    {
        return $this->hasOne(EmployeeAssignment::class)
            ->whereNull('end_date')
            ->latest('start_date')
            ->with('OrgUnit')
            ->first()?->OrgUnit; // return OrgUnit instance
    }
    public function currentPositionHistory()
    {
        return $this->hasOne(EmployeeAssignment::class)
            ->whereNull('end_date')
            ->latest('start_date');
    }

    public function currentUnitHistory()
    {
        return $this->hasOne(EmployeeAssignment::class)
            ->whereNull('end_date')
            ->latest('start_date');
    }

    public function latestHistory()
    {
        return $this->hasOne(EmployeeAssignment::class)->latest('start_date');
    }

    // ========== Missions Relations ==========

    /**
     * المهام التي أنشأها المستخدم
     */
    public function createdMissions()
    {
        return $this->hasMany(Mission::class, 'creator_id');
    }

    /**
     * المهام التي يقودها المستخدم
     */
    public function ledMissions()
    {
        return $this->hasMany(Mission::class, 'leader_id');
    }

    /**
     * المهام التي المستخدم عضو فيها
     */
    public function missions()
    {
        return $this->belongsToMany(Mission::class, 'mission_members')
            ->withPivot('role', 'can_create_tasks', 'can_view_all_tasks')
            ->withTimestamps();
    }

    /**
     * عضوية المستخدم في المهام
     */
    public function missionMemberships()
    {
        return $this->hasMany(MissionMember::class);
    }

    // ========== Tasks Relations ==========

    /**
     * المهام التي أنشأها المستخدم
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'creator_id');
    }

    /**
     * المهام المخصصة للمستخدم
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * المهام المشتركة (عبر task_assignees)
     */
    public function sharedTasks()
    {
        return $this->belongsToMany(Task::class, 'task_assignees')
            ->withPivot('status', 'notes', 'completed_at')
            ->withTimestamps();
    }

    // ========== Comments & Logs ==========

    /**
     * تعليقات المستخدم
     */
    public function taskComments()
    {
        return $this->hasMany(TaskComment::class);
    }

    /**
     * سجلات نشاط المستخدم
     */
    public function taskLogs()
    {
        return $this->hasMany(TaskLog::class);
    }

    /**
     * المرفقات التي رفعها المستخدم
     */
    public function uploadedAttachments()
    {
        return $this->hasMany(TaskAttachment::class, 'uploaded_by');
    }

    // ========== Helper Methods ==========

    /**
     * هل المستخدم قائد في مهمة معينة؟
     */
    public function isLeaderOf(Mission $mission): bool
    {
        return $mission->leader_id === $this->id;
    }

    /**
     * هل المستخدم عضو في مهمة معينة؟
     */
    public function isMemberOf(Mission $mission): bool
    {
        return $this->missionMemberships()
            ->where('mission_id', $mission->id)
            ->exists();
    }

    /**
     * الحصول على دور المستخدم في مهمة معينة
     */
    public function getRoleIn(Mission $mission): ?string
    {
        $membership = $this->missionMemberships()
            ->where('mission_id', $mission->id)
            ->first();

        return $membership?->role;
    }

    /**
     * هل يمكن للمستخدم إنشاء مهام في هذه المهمة؟
     */
    public function canCreateTasksIn(Mission $mission): bool
    {
        if ($this->isLeaderOf($mission)) {
            return true;
        }

        $membership = $this->missionMemberships()
            ->where('mission_id', $mission->id)
            ->first();

        return $membership && $membership->can_create_tasks;
    }

    /**
     * هل يمكن للمستخدم رؤية جميع المهام في هذه المهمة؟
     */
    public function canViewAllTasksIn(Mission $mission): bool
    {
        if ($this->isLeaderOf($mission)) {
            return true;
        }

        $membership = $this->missionMemberships()
            ->where('mission_id', $mission->id)
            ->first();

        return $membership && $membership->can_view_all_tasks;
    }

    /**
     * احصائيات المستخدم
     */
    public function getStatistics(): array
    {
        return [
            'total_missions' => $this->missions()->count(),
            'led_missions' => $this->ledMissions()->count(),
            'total_tasks' => $this->assignedTasks()->count(),
            'completed_tasks' => $this->assignedTasks()->where('status', 'completed')->count(),
            'pending_tasks' => $this->assignedTasks()->where('status', 'pending')->count(),
            'overdue_tasks' => $this->assignedTasks()
                ->where('status', '!=', 'completed')
                ->where('due_date', '<', now())
                ->count(),
        ];
    }

    /**
     * المهام القادمة (خلال أسبوع)
     */
    public function getUpcomingTasks(int $days = 7)
    {
        return $this->assignedTasks()
            ->where('status', '!=', 'completed')
            ->whereBetween('due_date', [now(), now()->addDays($days)])
            ->orderBy('due_date')
            ->get();
    }

    /**
     * المهام المتأخرة
     */
    public function getOverdueTasks()
    {
        return $this->assignedTasks()
            ->where('status', '!=', 'completed')
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->get();
    }
}
