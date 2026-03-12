<?php

namespace Database\Seeders;

use App\Models\AppointmentRequest;
use App\Models\CalendarSlotProposal;
use App\Models\User;
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

                // Create some slot proposals for in_progress appointments
                if ($appointment->status === 'in_progress' && rand(0, 1)) {
                    $secretaryUserId = $users->first()->id;
                    $slotCount = rand(2, 4);
                    
                    for ($j = 0; $j < $slotCount; $j++) {
                        $startDate = Carbon::now()->addDays(rand(1, 30))->setHour(rand(9, 15))->setMinute(0);
                        CalendarSlotProposal::create([
                            'appointment_request_id' => $appointment->id,
                            'start_date' => $startDate,
                            'end_date' => $startDate->copy()->addHours(1),
                            'location' => rand(0, 1) ? 'قاعة الاجتماعات الرئيسية' : 'م مكتب الوزير',
                            'status' => 'proposed',
                            'created_by' => $secretaryUserId,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                $this->command->warn("Failed to create appointment: " . $e->getMessage());
                continue;
            }
        }

        $this->command->info('Successfully seeded 20 appointment requests with slot proposals!');
    }
}
