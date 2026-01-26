<?php

namespace Database\Seeders;

use App\Models\Mission;
use App\Models\Task;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowTeam;
use App\Models\WorkflowStage;
use App\Models\WorkflowInstance;
use App\Models\WorkflowTransition;
use App\Services\WorkflowService;
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

        // Create workflow for tasks
        $workflow = Workflow::firstOrCreate(
            ['entity_type' => Task::class],
            [
                'name' => 'Task Approval Workflow',
                'description' => 'Workflow for task approval and review',
                'is_active' => true,
            ]
        );

        // Create workflow teams
        $reviewTeam = WorkflowTeam::firstOrCreate(
            ['name' => 'Task Review Team'],
            ['description' => 'Team responsible for reviewing tasks']
        );

        $approvalTeam = WorkflowTeam::firstOrCreate(
            ['name' => 'Task Approval Team'],
            ['description' => 'Team responsible for approving tasks']
        );

        // Add users to teams
        if ($users->count() >= 4) {
            $reviewTeam->users()->syncWithoutDetaching([$users[0]->id, $users[1]->id]);
            $approvalTeam->users()->syncWithoutDetaching([$users[2]->id, $users[3]->id]);
        }

        // Create workflow stages
        $stages = [];
        
        // Stage 1: Review
        $stage1 = WorkflowStage::firstOrCreate(
            [
                'workflow_id' => $workflow->id,
                'order' => 1,
            ],
            [
                'name' => 'مراجعة المهمة',
                'team_id' => $reviewTeam->id,
                'assignment_type' => 'team',
                'can_approve' => true,
                'can_return' => true,
                'can_reject' => true,
                'allowed_days' => 2,
            ]
        );
        $stages[] = $stage1;

        // Stage 2: Approval
        $stage2 = WorkflowStage::firstOrCreate(
            [
                'workflow_id' => $workflow->id,
                'order' => 2,
            ],
            [
                'name' => 'اعتماد المهمة',
                'team_id' => $approvalTeam->id,
                'assignment_type' => 'team',
                'can_approve' => true,
                'can_return' => false,
                'can_reject' => true,
                'allowed_days' => 1,
            ]
        );
        $stages[] = $stage2;

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

        $workflowService = app(WorkflowService::class);

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

            // Assign workflow to some tasks (70% chance)
            if (rand(0, 9) < 7) {
                try {
                    $workflowService->assignWorkflow($task, $workflow->id, $createdUsers[0], true); // auto submit
                    
                    $instance = $task->workflowInstance;
                    if ($instance) {
                        // Randomly move some tasks through workflow stages
                        $randomStage = rand(0, 1) ? $stages[0] : (rand(0, 1) ? $stages[1] : null);
                        
                        if ($randomStage) {
                            $instance->update([
                                'current_stage_id' => $randomStage->id,
                                'status' => 'in_progress',
                                'stage_due_at' => now()->addDays($randomStage->allowed_days),
                            ]);

                            // Create some transitions
                            $actor = $reviewTeam->users()->first() ?? $users->first();
                            
                            // Transition 1: Submit
                            WorkflowTransition::create([
                                'workflowable_type' => Task::class,
                                'workflowable_id' => $task->id,
                                'actor_id' => $createdUsers[0]->id,
                                'from_stage_id' => null,
                                'to_stage_id' => $stages[0]->id,
                                'action' => 'submit',
                                'comments' => 'تم إرسال المهمة للمراجعة',
                                'created_at' => now()->subDays(rand(1, 5)),
                            ]);

                            // Transition 2: Approve to next stage (if in second stage)
                            if ($randomStage->id === $stages[1]->id) {
                                WorkflowTransition::create([
                                    'workflowable_type' => Task::class,
                                    'workflowable_id' => $task->id,
                                    'actor_id' => $actor->id,
                                    'from_stage_id' => $stages[0]->id,
                                    'to_stage_id' => $stages[1]->id,
                                    'action' => 'approve',
                                    'comments' => 'تمت الموافقة على المراجعة',
                                    'created_at' => now()->subDays(rand(1, 3)),
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $this->command->warn("Failed to assign workflow to task {$task->id}: " . $e->getMessage());
                }
            }

            // إذا كانت المهمة مكتملة، أضف completed_at
            if ($task->status === 'completed') {
                $task->update([
                    'completed_at' => now()->subDays(rand(1, 3)),
                ]);

                // Complete workflow if exists
                if ($task->workflowInstance) {
                    $task->workflowInstance->update([
                        'status' => 'completed',
                        'current_stage_id' => null,
                    ]);
                }
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

                // Assign workflow to some tasks
                if (rand(0, 9) < 5) {
                    try {
                        $workflowService->assignWorkflow($task, $workflow->id, $users->first(), true);
                    } catch (\Exception $e) {
                        // Silent fail
                    }
                }
            }
        }

        $this->command->info('Successfully seeded ' . count($missions) . ' missions with tasks and workflow data!');
    }
}