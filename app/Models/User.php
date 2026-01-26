<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;
use App\Models\AppointmentRequest;
use App\Models\Step;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    /**
     * Get the user's avatar URL.
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Return a default "ui-avatars.com" image based on name
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
    }

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

    /**
     * Cache for user permissions (performance optimization)
     */
    protected ?array $cachedPermissions = null;

    public function sectors()
    {
        return $this->belongsToMany(Sector::class);
    }

    // ========== Calendar ==========
    public function calendarPermissions()
    {
        return $this->hasMany(CalendarPermission::class);
    }
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

    // ========== Calendar Delegations ==========

    /**
     * People who manage MY calendar (I granted them access)
     */
    public function delegates()
    {
        return $this->belongsToMany(User::class, 'calendar_delegations', 'manager_id', 'employee_id')
            ->wherePivot('is_active', true)
            ->withTimestamps();
    }

    /**
     * People whose calendars I manage (They shared with me)
     * This is what drives the "Shared with Me" list.
     */
    public function calendarsSharedWithMe()
    {
        return $this->belongsToMany(User::class, 'calendar_delegations', 'employee_id', 'manager_id')
            ->wherePivot('is_active', true)
            ->withTimestamps();
    }

    public function delegationsAsManager()
    {
        return $this->hasMany(CalendarDelegation::class, 'manager_id');
    }
    // ========== RBAC - Roles & Permissions ==========

    /**
     * The roles that belong to this user.
     * This is the ONLY source of permissions (RBAC compliant).
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withTimestamps();
    }

    /**
     * The currently active role (for role switching).
     */
    public function activeRole()
    {
        return $this->belongsTo(Role::class, 'active_role_id');
    }

    /**
     * Get the effective active role.
     * Returns active_role if set, otherwise first assigned role.
     */
    public function getActiveRole(): ?Role
    {
        return $this->activeRole ?? $this->roles->first();
    }


    /**
     * Assign a role to the user.
     */
    public function assignRole(Role|int $role): void
    {
        $roleId = $role instanceof Role ? $role->id : $role;
        $this->roles()->syncWithoutDetaching($roleId);
        $this->cachedPermissions = null; // Clear cache
    }

    /**
     * Remove a role from the user.
     */
    public function revokeRole(Role|int $role): void
    {
        $roleId = $role instanceof Role ? $role->id : $role;
        $this->roles()->detach($roleId);
        $this->cachedPermissions = null; // Clear cache
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    /**
     * Check if user has a specific permission.
     * 
     * CANONICAL RBAC RULE:
     * A user has a permission if and only if at least one 
     * assigned role grants that permission.
     */
    public function hasPermission(string $slug): bool
    {
        // Super Admin Bypass (user ID 1 always has all permissions)
        if ($this->id === 1) {
            return true;
        }

        // Check if the required slug is in the user's permission list
        return in_array($slug, $this->getPermissions());
    }

    /**
     * Get all permissions for this user (aggregated from all roles).
     * 
     * @return array Array of permission slugs
     */
    public function getPermissions(): array
    {
        // Return cached permissions if available
        if ($this->cachedPermissions !== null) {
            return $this->cachedPermissions;
        }

        // Get all permission slugs from all assigned roles
        $permissions = DB::table('permission_role')
            ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
            ->join('role_user', 'permission_role.role_id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $this->id)
            ->distinct()
            ->pluck('permissions.slug')
            ->toArray();

        // Cache the result
        $this->cachedPermissions = array_values($permissions);

        return $this->cachedPermissions;
    }

    /**
     * Clear the cached permissions (call after role changes).
     */
    public function clearPermissionCache(): void
    {
        $this->cachedPermissions = null;
    }

    /**
     * Get the active role (for UI display purposes).
     * Returns the first role or null if no roles assigned.
     */
    public function getPrimaryRole(): ?Role
    {
        return $this->roles()->first();
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

    // ========== WORKFLOW ENGINE ==========

    /**
     * Workflow teams this user belongs to
     */
    public function workflowTeams()
    {
        return $this->belongsToMany(WorkflowTeam::class, 'user_workflow_team')
            ->withTimestamps();
    }

    /**
     * Get all workflow items pending action by this user (based on team membership)
     * 
     * This includes Activities, AppointmentRequests, Steps, and any other models
     * that implement the HasWorkflow interface.
     */
    public function pendingWorkflowActivities()
    {
        $teamIds = $this->workflowTeams()->pluck('workflow_teams.id');

        // Get Activities
        $activities = Activity::whereHas('workflowInstance', function ($query) use ($teamIds) {
            $query->whereHas('currentStage', function ($q) use ($teamIds) {
                $q->whereIn('team_id', $teamIds);
            })->whereNotIn('status', ['completed', 'draft', 'rejected']);
        })->get();

        // Get AppointmentRequests
        $appointments = AppointmentRequest::whereHas('workflowInstance', function ($query) use ($teamIds) {
            $query->whereHas('currentStage', function ($q) use ($teamIds) {
                $q->whereIn('team_id', $teamIds);
            })->whereNotIn('status', ['completed', 'draft', 'rejected']);
        })->get();

        // Get Steps
        $steps = Step::whereHas('workflowInstance', function ($query) use ($teamIds) {
            $query->whereHas('currentStage', function ($q) use ($teamIds) {
                $q->whereIn('team_id', $teamIds);
            })->whereNotIn('status', ['completed', 'draft', 'rejected']);
        })->get();

        // Merge all workflow items and add type identifier
        return $activities->map(function ($item) {
            $item->workflow_item_type = 'activity';
            return $item;
        })->merge($appointments->map(function ($item) {
            $item->workflow_item_type = 'appointment';
            return $item;
        }))->merge($steps->map(function ($item) {
            $item->workflow_item_type = 'step';
            return $item;
        }));
    }

    /**
     * Check if user can act on a specific activity (is member of the activity's current stage team)
     */
    public function canActOnActivity(Activity $activity): bool
    {
        $currentStage = $activity->currentStage;

        if (!$currentStage) {
            return false;
        }

        $stageTeamId = $currentStage->team_id;

        return $this->workflowTeams()
            ->where('workflow_teams.id', $stageTeamId)
            ->exists();
    }
}
