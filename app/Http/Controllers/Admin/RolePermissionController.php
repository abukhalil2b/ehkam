<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Role-Permission Assignment Controller
 * 
 * Manages the assignment of permissions to roles.
 * Permissions are ONLY assigned to roles, not directly to users (RBAC).
 */
class RolePermissionController extends Controller
{
    /**
     * Display role's permissions with checkbox matrix.
     */
    public function index(Role $role)
    {
        $permissions = Permission::leftJoin(
            'permission_role',
            fn($join) => $join->on('permissions.id', '=', 'permission_role.permission_id')
                ->where('permission_role.role_id', $role->id)
        )->select(
                'permissions.id as permissionId',
                'permissions.title as permissionTitle',
                'permissions.description as permissionDescr',
                'permissions.slug as permissionSlug',
                'permissions.category as permissionCate',
                DB::raw('IF(permission_role.permission_id IS NULL, false, true) as selected')
            )
            ->orderBy('permissions.category')
            ->orderBy('permissions.title')
            ->get();

        // Group permissions by category for better UX
        $groupedPermissions = $permissions->groupBy('permissionCate');

        return view('admin.role.permissions', compact('permissions', 'groupedPermissions', 'role'));
    }

    /**
     * Update role's permissions (bulk sync).
     */
    public function update(Role $role, Request $request)
    {
        // Validate request
        $request->validate([
            'permissionIds' => 'nullable|array',
            'permissionIds.*' => 'exists:permissions,id',
        ]);

        // Sync permissions using the Role model relationship
        $permissionIds = $request->input('permissionIds', []);
        $role->permissions()->sync($permissionIds);

        return back()->with('success', 'تم تحديث صلاحيات الدور بنجاح');
    }
}
