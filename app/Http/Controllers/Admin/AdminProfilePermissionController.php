<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Permission;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProfilePermissionController extends Controller
{

    public function index(Profile $profile)
    {
        $permissions = Permission::leftJoin(
            'profile_permission',
            fn($join) => $join->on('permissions.id', '=', 'profile_permission.permission_id')
                ->where('profile_permission.profile_id', $profile->id)
        )->select(
            'permissions.id as permissionId',
            'permissions.title as permissionTitle',
            'permissions.description as permissionDescr',
            'permissions.slug as permissionSlug',
            'permissions.category as permissionCate',
            DB::raw('IF(profile_permission.permission_id IS NULL, false, true) as selected')
        )
            ->orderBy('permissions.category')
            ->orderBy('permissions.title')
            ->get();

        return view('admin.profile_permission.index', compact('permissions', 'profile'));
    }


    public function profilePermissionUpdate(Profile $profile, Request $request)
    {
        if ($request->permissionIds) {

            DB::table('profile_permission')
                ->where('profile_id', $profile->id)
                ->delete();

            foreach ($request->permissionIds as $id) {
                DB::table('profile_permission')
                    ->insert(['profile_id' => $profile->id, 'permission_id' => $id]);
            }
        }

        return back();
    }
}
