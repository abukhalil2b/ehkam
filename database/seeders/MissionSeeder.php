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
        $mission = Mission::create([
            'title' => 'new mission',
            'description' => 'new mission',
            'creator_id' => 3,
            'leader_id' => 3,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays(60),
            'order' => 1,
        ]);
        $createdUsers = User::where('id', '>', 2)->get();

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
                'creator_id' => $createdUsers[0]->id, // أحمد هو من أنشأ كل المهام
                'status' => $index % 5 === 0 ? 'completed' : 'pending', // بعض المهام مكتملة
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
    }
}