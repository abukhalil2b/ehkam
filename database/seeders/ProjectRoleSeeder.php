<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ProjectRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Permissions
        $permissions = [
            [
                'title' => 'إنشاء مشاريع',
                'slug' => 'create_projects',
                'description' => 'يسمح بإنشاء مشاريع جديدة',
            ],
            [
                'title' => 'تقديم مشاريع',
                'slug' => 'submit_projects',
                'description' => 'يسمح بتقديم المشاريع للمراجعة',
            ],
            [
                'title' => 'اعتماد مشاريع',
                'slug' => 'approve_projects',
                'description' => 'يسمح باعتماد ورفض المشاريع',
            ],
        ];

        $createdPermissions = [];
        foreach ($permissions as $perm) {
            $createdPermissions[$perm['slug']] = Permission::firstOrCreate(
                ['slug' => $perm['slug']],
                $perm
            );
        }

        // 2. Create Roles
        $creatorRole = Role::firstOrCreate(
            ['slug' => 'project_creator'],
            [
                'title' => 'منشئ مشاريع',
                'description' => 'مستخدم يمكنه إنشاء وتقديم المشاريع',
            ]
        );

        $approverRole = Role::firstOrCreate(
            ['slug' => 'project_approver'],
            [
                'title' => 'معتمد مشاريع',
                'description' => 'فريق أو مدير يعتمد مسودات المشاريع',
            ]
        );

        // 3. Assign Permissions to Roles
        $creatorRole->syncPermissions([
            $createdPermissions['create_projects']->id,
            $createdPermissions['submit_projects']->id,
        ]);

        $approverRole->syncPermissions([
            $createdPermissions['approve_projects']->id,
        ]);

        $this->command->info('تم إنشاء أدوار سير عمل المشاريع بنجاح!');
    }
}
