<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Profile;

class ActivityWorkflowSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Permissions
        $permissions = [
            'activity.set_target' => 'Set targets for activities',
            'activity.execute' => 'Execute activity steps and upload evidence',
            'activity.verify' => 'Verify activity evidence',
            'activity.approve' => 'Final approval of activity',
        ];

        foreach ($permissions as $slug => $desc) {
            Permission::firstOrCreate(
                ['slug' => $slug],
                ['title' => ucwords(str_replace('.', ' ', $slug)), 'category' => 'activity', 'description' => $desc]
            );
        }

        // 2. Create Profiles (Roles)
        $roles = [
            'Planner' => ['activity.set_target'],
            'Executor' => ['activity.execute'],
            'Quality Assurance' => ['activity.verify'],
            'Project Manager' => ['activity.approve'],
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $profile = Profile::firstOrCreate(['title' => $roleName]);

            // Sync permissions
            $permIds = Permission::whereIn('slug', $rolePerms)->pluck('id');
            $profile->permissions()->sync($permIds);
        }
    }
}
