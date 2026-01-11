<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Profile;

class IndicatorWorkflowSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Permissions
        $permissions = [
            'indicator.report' => 'Report achievement data for indicators',
            'indicator.verify' => 'Verify indicator achievement data',
            'indicator.approve' => 'Final approval of indicator reports',
        ];

        foreach ($permissions as $slug => $desc) {
            Permission::firstOrCreate(
                ['slug' => $slug],
                ['title' => ucwords(str_replace('.', ' ', $slug)), 'category' => 'indicator', 'description' => $desc]
            );
        }

        // 2. Assign to Profiles (Roles)
        // Reuse existing profiles from Activity Workflow
        $roles = [
            'Planner' => ['indicator.report'], // Planner or Executor can report? Let's give it to Planner for now.
            'Executor' => ['indicator.report'], // Or Executor. Let's give both for flexibility.
            'Quality Assurance' => ['indicator.verify'],
            'Project Manager' => ['indicator.approve'],
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $profile = Profile::where('title', $roleName)->first();

            if ($profile) {
                // Get IDs of new permissions
                $permIds = Permission::whereIn('slug', $rolePerms)->pluck('id');
                // Attach without detaching existing ones
                $profile->permissions()->syncWithoutDetaching($permIds);
            }
        }
    }
}
