<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Profile;

class StepWorkflowSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Permissions
        $permissions = [
            // Step Workflow Permissions
            'step.set_target' => 'Set targets for steps',
            'step.execute' => 'Execute steps and upload evidence',
            'step.verify' => 'Verify step evidence',
            'step.approve' => 'Final approval of step',

            // Admin Dashboard Permission
            'admin.workflow.index' => 'View Workflow Dashboard',

            // Position Management Permissions
            'admin_position.create' => 'Create new job positions',
            'admin_position.edit' => 'Edit job positions',
            'admin_position.delete' => 'Delete job positions',
        ];

        foreach ($permissions as $slug => $desc) {
            Permission::firstOrCreate(
                ['slug' => $slug],
                ['title' => ucwords(str_replace('.', ' ', $slug)), 'category' => 'step', 'description' => $desc]
            );
        }

        // 2. Create Profiles (Roles) - reusing existing roles if possible or defining new scopes
        // For simplicity, we'll assign these to the same roles as Activities for now, or ensure the admin/manager has them.

        $roles = [
            'Planner' => ['step.set_target'],
            'Executor' => ['step.execute'],
            'Quality Assurance' => ['step.verify'],
            'Project Manager' => ['step.approve', 'admin.workflow.index'],
            'Admin' => [
                'admin.workflow.index',
                'step.set_target',
                'step.execute',
                'step.verify',
                'step.approve',
                'admin_position.create',
                'admin_position.edit',
                'admin_position.delete'
            ], // Ensure Admin has access
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $profile = Profile::firstOrCreate(['title' => $roleName]);

            // Sync permissions (append to existing)
            $permIds = Permission::whereIn('slug', $rolePerms)->pluck('id');
            $profile->permissions()->syncWithoutDetaching($permIds);
        }
    }
}
