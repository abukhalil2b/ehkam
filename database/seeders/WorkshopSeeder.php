<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workshop;
use App\Models\WorkshopDay;
use App\Models\WorkshopAttendance;
use App\Models\WorkshopCheckin;
use App\Models\User;
use Illuminate\Support\Str;

class WorkshopSeeder extends Seeder
{
    public function run()
    {
        // 1. Create a Workshop
        $workshop = Workshop::create([
            'title' => 'ورشة العمل التجريبية: الذكاء الاصطناعي',
            'location' => 'القاعة الرئيسية - مسقط',
            'description' => 'ورشة عمل مكثفة لمدة 3 أيام حول أساسيات وتطبيقات الذكاء الاصطناعي.',
            'is_active' => true,
            'created_by' => User::first()?->id ?? 1, // Fallback to ID 1 if no users
            'starts_at' => now(),
            'ends_at' => now()->addDays(3),
        ]);

        $this->command->info("Created Workshop: {$workshop->title}");

        // 2. Create 3 Days
        $days = [];
        for ($i = 0; $i < 3; $i++) {
            $days[] = WorkshopDay::create([
                'workshop_id' => $workshop->id,
                'day_date' => now()->addDays($i),
                'label' => "اليوم " . ($i + 1),
                'is_active' => true,
                'attendance_hash' => Str::random(64),
            ]);
        }

        // 3. Create 10 Participants
        $participants = [];
        for ($i = 1; $i <= 10; $i++) {
            $participants[] = WorkshopAttendance::create([
                'workshop_id' => $workshop->id,
                'attendee_key' => Str::uuid(), // Simulate unique device/IP
                'attendee_name' => "مشارك تجريبي {$i}",
                'job_title' => 'موظف',
                'department' => 'تقنية المعلومات',
            ]);
        }

        // 4. Record random attendance (Check-ins)
        // Day 1: 8 attendees
        // Day 2: 6 attendees
        // Day 3: 9 attendees

        $this->checkInParticipants($days[0], array_slice($participants, 0, 8));
        $this->checkInParticipants($days[1], array_slice($participants, 2, 8)); // different subset
        $this->checkInParticipants($days[2], array_slice($participants, 0, 9));

        $this->command->info("Seeding completed! You can check the reports now.");
    }

    private function checkInParticipants($day, $participants)
    {
        foreach ($participants as $p) {
            WorkshopCheckin::create([
                'workshop_day_id' => $day->id,
                'workshop_attendance_id' => $p->id,
                'status' => 'present',
                'checkin_time' => now(),
                'ip_address' => '127.0.0.1',
            ]);
        }
    }
}
