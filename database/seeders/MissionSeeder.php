<?php

namespace Database\Seeders;

use App\Models\Mission;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MissionSeeder extends Seeder
{
    public function run()
    {
        // Get or create users
        $users = User::take(10)->get();
        if ($users->count() < 4) {
            $this->command->warn('Need at least 4 users to seed missions. Creating basic mission only.');
            return;
        }

        // Create missions
        $missionTitles = [
            'مشروع تطوير النظام الإلكتروني',
            'مبادرة تحسين الخدمات',
            'خطة التطوير الاستراتيجي',
            'مشروع التوعية والتثقيف',
        ];

        $missionDescriptions = [
            'مشروع شامل لتطوير النظام الإلكتروني وتحسين الأداء',
            'مبادرة لتحسين جودة الخدمات المقدمة للمواطنين',
            'خطة استراتيجية شاملة للتطوير والتحسين',
            'مشروع توعوي لزيادة الوعي بالخدمات والبرامج',
        ];

        $missions = [];
        for ($i = 0; $i < 4; $i++) {
            $leader = $users->random();
            $missions[] = Mission::create([
                'title' => $missionTitles[$i],
                'description' => $missionDescriptions[$i],
                'creator_id' => $users->first()->id,
                'leader_id' => $leader->id,
                'status' => ['active', 'active', 'completed', 'active'][$i],
                'start_date' => now()->subDays(rand(0, 30)),
                'end_date' => now()->addDays(rand(30, 90)),
                'order' => $i + 1,
            ]);

            // Add members to mission
            $memberIds = $users->where('id', '!=', $leader->id)->random(rand(2, 4))->pluck('id');
            foreach ($memberIds as $memberId) {
                DB::table('mission_members')->insert([
                    'mission_id' => $missions[$i]->id,
                    'user_id' => $memberId,
                    'role' => 'member',
                    'can_create_tasks' => true,
                    'can_view_all_tasks' => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Add leader as member
            DB::table('mission_members')->insert([
                'mission_id' => $missions[$i]->id,
                'user_id' => $leader->id,
                'role' => 'leader',
                'can_create_tasks' => true,
                'can_view_all_tasks' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $mission = $missions[0]; // Use first mission for tasks
        $createdUsers = $users;

        // إنشاء المهام
        $tasks = [
            // مهام أحمد
            [
                'title' => 'متابعة بريد الوزارة',
                'description' => 'المتابعة اليومية لبريد الوزارة والرد على الاستفسارات',
                'priority' => 'high',
                'assigned_to' => $createdUsers[0]->id,
                'is_private' => false,
                'due_date' => now()->addDays(7),
            ],
            [
                'title' => 'توزيع الأدوار على الخطة',
                'description' => 'توزيع المهام والأدوار على الموظفين حسب الخطة الموضوعة',
                'priority' => 'medium',
                'assigned_to' => $createdUsers[0]->id,
                'is_private' => false,
                'due_date' => now()->addDays(5),
            ],
            [
                'title' => 'تصميم استمارة الحضور',
                'description' => 'تصميم نموذج حضور الاجتماعات والفعاليات',
                'priority' => 'low',
                'assigned_to' => $createdUsers[0]->id,
                'is_private' => false,
                'due_date' => now()->addDays(10),
            ],

            // مهام حمدان
            [
                'title' => 'مراجعة مسودة خطة الوزارة 2026',
                'description' => 'مراجعة شاملة لمسودة الخطة وتقديم الملاحظات والتوصيات',
                'priority' => 'high',
                'assigned_to' => $createdUsers[1]->id,
                'is_private' => false,
                'due_date' => now()->addDays(3),
            ],
            [
                'title' => 'التنسيق مع الإدارات للاجتماع القادم',
                'description' => 'التواصل مع الإدارات المختلفة لتحديد موعد الاجتماع وجدول الأعمال',
                'priority' => 'medium',
                'assigned_to' => $createdUsers[1]->id,
                'is_private' => false,
                'due_date' => now()->addDays(4),
            ],
            [
                'title' => 'زيارة القطاعات والتوعية',
                'description' => 'زيارة القطاعات المختلفة وتوعية الموظفين بالخطط والأهداف الجديدة',
                'priority' => 'medium',
                'assigned_to' => $createdUsers[1]->id,
                'is_private' => false,
                'due_date' => now()->addDays(14),
            ],

            // مهام حميد
            [
                'title' => 'متابعة إنجاز المشاريع والأدلة الداعمة',
                'description' => 'متابعة تقدم المشاريع وجمع الأدلة الداعمة للإنجازات',
                'priority' => 'high',
                'assigned_to' => $createdUsers[2]->id,
                'is_private' => false,
                'due_date' => now()->addDays(6),
            ],
            [
                'title' => 'كتابة محضر الاجتماع',
                'description' => 'تدوين محضر الاجتماع وتوثيق القرارات والتوصيات المتخذة',
                'priority' => 'medium',
                'assigned_to' => $createdUsers[2]->id,
                'is_private' => false,
                'due_date' => now()->addDays(2),
            ],
            [
                'title' => 'تحليل البيانات الإحصائية',
                'description' => 'تحليل البيانات المجمعة واستخلاص النتائج والتوصيات',
                'priority' => 'low',
                'assigned_to' => $createdUsers[2]->id,
                'is_private' => false,
                'due_date' => now()->addDays(12),
            ],

            // مهام مسعود
            [
                'title' => 'كتابة التقرير النهائي',
                'description' => 'إعداد التقرير النهائي الشامل عن سير العمل والإنجازات المحققة',
                'priority' => 'high',
                'assigned_to' => $createdUsers[3]->id,
                'is_private' => false,
                'due_date' => now()->addDays(8),
            ],
            [
                'title' => 'تصميم الاستبانة لقياس رضى المستفيدين',
                'description' => 'تصميم استبيان شامل لقياس مستوى رضا المستفيدين عن الخدمات المقدمة',
                'priority' => 'medium',
                'assigned_to' => $createdUsers[3]->id,
                'is_private' => false,
                'due_date' => now()->addDays(9),
            ],

            // مهمة خاصة كمثال
            [
                'title' => 'مراجعة الأداء الشخصي',
                'description' => 'مراجعة وتقييم الأداء الشخصي للربع الأخير',
                'priority' => 'low',
                'assigned_to' => $createdUsers[1]->id,
                'is_private' => true, // مهمة خاصة
                'due_date' => now()->addDays(15),
            ],
        ];


        foreach ($tasks as $index => $taskData) {
            $task = Task::create([
                'mission_id' => $mission->id,
                'creator_id' => $createdUsers[0]->id,
                'status' => ['pending', 'in_progress', 'completed', 'pending', 'in_progress'][$index % 5],
                'order' => $index,
                ...$taskData,
            ]);

            // إضافة log للإنشاء
            $task->logs()->create([
                'user_id' => $createdUsers[0]->id,
                'action' => 'created',
                'new_values' => $task->toArray(),
            ]);



            // إذا كانت المهمة مكتملة، أضف completed_at
            if ($task->status === 'completed') {
                $task->update([
                    'completed_at' => now()->subDays(rand(1, 3)),
                ]);


            }
        }

        // Create tasks for other missions too
        foreach (array_slice($missions, 1) as $m) {
            for ($i = 0; $i < rand(3, 6); $i++) {
                $task = Task::create([
                    'mission_id' => $m->id,
                    'title' => 'مهمة ' . ($i + 1) . ' - ' . $m->title,
                    'description' => 'وصف تفصيلي للمهمة',
                    'priority' => ['high', 'medium', 'low'][rand(0, 2)],
                    'status' => ['pending', 'in_progress', 'completed'][rand(0, 2)],
                    'creator_id' => $m->leader_id,
                    'assigned_to' => $users->where('id', '!=', $m->leader_id)->random()->id,
                    'is_private' => rand(0, 1),
                    'due_date' => now()->addDays(rand(1, 30)),
                    'order' => $i,
                ]);


            }
        }

        $this->command->info('Successfully seeded ' . count($missions) . ' missions with tasks and workflow data!');
    }
}