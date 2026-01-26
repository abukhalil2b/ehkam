<?php

namespace Database\Seeders;

use App\Models\AppointmentRequest;
use App\Models\CalendarSlotProposal;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowTeam;
use App\Models\WorkflowStage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentRequestSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have users
        $users = User::take(10)->get();
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        if ($users->count() < 2) {
            $this->command->warn('Need at least 2 users to create appointment requests.');
            return;
        }

        // Get or create workflow for appointments
        $workflow = Workflow::firstOrCreate(
            ['entity_type' => AppointmentRequest::class],
            [
                'name' => 'Appointment Request Workflow',
                'description' => 'Workflow for managing appointment requests',
                'is_active' => true,
            ]
        );

        // Create workflow teams if they don't exist
        $managerTeam = WorkflowTeam::firstOrCreate(
            ['name' => 'Managers Team'],
            ['description' => 'Team of managers who approve appointments']
        );

        $secretaryTeam = WorkflowTeam::firstOrCreate(
            ['name' => 'Secretaries Team'],
            ['description' => 'Team of secretaries who schedule appointments']
        );

        // Add users to teams
        if ($users->count() >= 2) {
            $managerTeam->users()->syncWithoutDetaching([$users[0]->id, $users[1]->id]);
            $secretaryTeam->users()->syncWithoutDetaching([$users[2]->id ?? $users[0]->id]);
        }

        // Create workflow stages
        $stages = [];
        
        // Stage 1: Manager Review
        $stage1 = WorkflowStage::firstOrCreate(
            [
                'workflow_id' => $workflow->id,
                'order' => 1,
            ],
            [
                'name' => 'مراجعة المدير',
                'team_id' => $managerTeam->id,
                'assignment_type' => 'team',
                'can_approve' => true,
                'can_return' => true,
                'can_reject' => true,
                'allowed_days' => 3,
            ]
        );
        $stages[] = $stage1;

        // Stage 2: Secretary Scheduling
        $stage2 = WorkflowStage::firstOrCreate(
            [
                'workflow_id' => $workflow->id,
                'order' => 2,
            ],
            [
                'name' => 'جدولة السكرتير',
                'team_id' => $secretaryTeam->id,
                'assignment_type' => 'team',
                'can_approve' => true,
                'can_return' => false,
                'can_reject' => false,
                'allowed_days' => 5,
            ]
        );
        $stages[] = $stage2;

        // Create dummy appointment requests
        $subjects = [
            'طلب موعد لمناقشة الميزانية',
            'طلب موعد لمراجعة الخطة الاستراتيجية',
            'طلب موعد لعرض التقرير السنوي',
            'طلب موعد لمناقشة المشاريع الجديدة',
            'طلب موعد لاجتماع طارئ',
            'طلب موعد لمراجعة الأداء',
            'طلب موعد لعرض النتائج',
            'طلب موعد لمناقشة التعيينات',
        ];

        $descriptions = [
            'أرغب في مناقشة تفاصيل الميزانية للعام القادم',
            'نحتاج لمراجعة الخطة الاستراتيجية قبل اعتمادها',
            'عرض التقرير السنوي يحتاج إلى موافقة',
            'مناقشة المشاريع الجديدة المخطط لها',
            'اجتماع طارئ يتطلب موافقة فورية',
            'مراجعة أداء الفريق والنتائج المحققة',
            'عرض النتائج النهائية للمشروع',
            'مناقشة التعيينات الجديدة في القسم',
        ];

        $priorities = ['low', 'normal', 'high', 'urgent'];
        $statuses = ['draft', 'in_progress', 'booked'];

        $appointments = [];

        for ($i = 0; $i < 20; $i++) {
            $requester = $users->random();
            $minister = $users->where('id', '!=', $requester->id)->random() ?? $users->first();
            
            $appointments[] = [
                'requester_id' => $requester->id,
                'minister_id' => $minister->id,
                'subject' => $subjects[array_rand($subjects)],
                'description' => $descriptions[array_rand($descriptions)],
                'priority' => $priorities[array_rand($priorities)],
                'status' => $statuses[array_rand($statuses)],
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30)),
            ];
        }

        // Insert appointments
        foreach ($appointments as $appointmentData) {
            try {
                $appointment = AppointmentRequest::create($appointmentData);

                // Create workflow instance for some appointments
                if (rand(0, 1)) {
                    $currentStageId = null;
                    if ($appointment->status === 'in_progress' && !empty($stages)) {
                        $currentStageId = $stages[array_rand($stages)]->id;
                    }

                    DB::table('workflow_instances')->insert([
                        'workflowable_type' => AppointmentRequest::class,
                        'workflowable_id' => $appointment->id,
                        'workflow_id' => $workflow->id,
                        'status' => $appointment->status === 'draft' ? 'draft' : 'in_progress',
                        'current_stage_id' => $currentStageId,
                        'creator_id' => $appointment->requester_id,
                        'stage_due_at' => $appointment->status === 'in_progress' ? Carbon::now()->addDays(rand(1, 5)) : null,
                        'created_at' => $appointment->created_at,
                        'updated_at' => $appointment->updated_at,
                    ]);

                    // Create some slot proposals for in_progress appointments
                    if ($appointment->status === 'in_progress' && rand(0, 1)) {
                        $secretaryUserId = $secretaryTeam->users()->first()->id ?? $users->first()->id;
                        $slotCount = rand(2, 4);
                        
                        for ($j = 0; $j < $slotCount; $j++) {
                            $startDate = Carbon::now()->addDays(rand(1, 30))->setHour(rand(9, 15))->setMinute(0);
                            CalendarSlotProposal::create([
                                'appointment_request_id' => $appointment->id,
                                'start_date' => $startDate,
                                'end_date' => $startDate->copy()->addHours(1),
                                'location' => rand(0, 1) ? 'قاعة الاجتماعات الرئيسية' : 'مكتب الوزير',
                                'status' => 'proposed',
                                'created_by' => $secretaryUserId,
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->command->warn("Failed to create appointment: " . $e->getMessage());
                continue;
            }
        }

        $this->command->info('Successfully seeded 20 appointment requests with workflow instances and slot proposals!');
    }
}
