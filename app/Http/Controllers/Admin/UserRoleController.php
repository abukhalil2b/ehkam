<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * UserRoleController - Manages user-role assignments
 * 
 * Handles assigning roles to users (RBAC).
 * Users receive permissions ONLY through their assigned roles.
 */
class UserRoleController extends Controller
{
    /**
     * Display users with their roles for a specific role.
     */
    public function showRoleUsers(Role $role)
    {
        $role->load('users');
        $allUsers = User::orderBy('name')->get();

        return view('admin.role.users', compact('role', 'allUsers'));
    }

    /**
     * Update users assigned to a role.
     */
    public function updateRoleUsers(Role $role, Request $request)
    {
        $request->validate([
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = $request->input('user_ids', []);
        $role->users()->sync($userIds);

        return back()->with('success', 'تم تحديث مستخدمي الدور بنجاح');
    }

    /**
     * Display roles for a specific user.
     */
    public function showUserRoles(User $user)
    {
        $user->load('roles');
        $allRoles = Role::orderBy('title')->get();

        return view('admin.user.roles', compact('user', 'allRoles'));
    }

    /**
     * Update roles assigned to a user.
     */
    public function updateUserRoles(User $user, Request $request)
    {
        $request->validate([
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $roleIds = $request->input('role_ids', []);
        $user->roles()->sync($roleIds);
        $user->clearPermissionCache();

        return back()->with('success', 'تم تحديث أدوار المستخدم بنجاح');
    }
}
