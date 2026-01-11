<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalendarEventsSeeder extends Seeder
{
    public function run()
    {
        // 1. Setup Configuration
        $userIds = range(2, 10); // Users IDs 2 to 11
        $adminId = 1; // The creator
        $year = 2026;
        $totalEvents = 100;

        $types = [
            'meeting'     => ['label' => 'اجتماع', 'color' => '#2563eb', 'duration_min' => 60, 'duration_max' => 120],
            'program'     => ['label' => 'برنامج', 'color' => '#16a34a', 'duration_min' => 120, 'duration_max' => 300], // Up to 5 hours
            'conference'  => ['label' => 'مؤتمر', 'color' => '#7c3aed', 'duration_days' => [2, 3]], // Multi-day
            'competition' => ['label' => 'مسابقة', 'color' => '#db2777', 'duration_days' => [1, 2]]
        ];

        $titles = [
            'meeting' => ['اجتماع مراجعة الأداء', 'اجتماع الفريق الأسبوعي', 'نقاش خطة الربع الأول', 'اجتماع مع قسم الموارد البشرية', 'اجتماع الميزانية'],
            'program' => ['برنامج تأهيل القيادات', 'ورشة عمل التقنيات الحديثة', 'برنامج الأمن السيبراني', 'دورة إدارة المشاريع', 'ورشة الذكاء الاصطناعي'],
            'conference' => ['مؤتمر التحول الرقمي', 'المؤتمر السنوي للابتكار', 'ملتقى تبادل الخبرات', 'مؤتمر التقنية المالية'],
            'competition' => ['مسابقة أفضل مشروع تقني', 'هاكاثون البرمجة', 'مسابقة القرآن الكريم', 'مسابقة التصميم الإبداعي']
        ];

        $events = [];

        // 2. Generate Data
        for ($i = 0; $i < $totalEvents; $i++) {
            
            // Randomly select type and user
            $typeKey = array_rand($types);
            $typeConfig = $types[$typeKey];
            $targetUserId = $userIds[array_rand($userIds)];
            
            // Generate Start Date (Avoid Fridays/Saturdays mostly)
            $month = rand(1, 12);
            $day = rand(1, 28);
            $hour = rand(8, 13); // 8 AM to 1 PM start
            
            $startDate = Carbon::create($year, $month, $day, $hour, 0, 0);
            
            // Adjust if it's weekend (Friday/Saturday) -> Move to Sunday
            if ($startDate->isFriday()) $startDate->addDays(2);
            if ($startDate->isSaturday()) $startDate->addDay();

            // Calculate End Date
            $endDate = $startDate->copy();
            
            if (isset($typeConfig['duration_days'])) {
                // Multi-day event
                $daysToAdd = $typeConfig['duration_days'][array_rand($typeConfig['duration_days'])];
                $endDate->addDays($daysToAdd - 1)->setHour(14)->setMinute(0); // End at 2 PM on the last day
            } else {
                // Single day hours event
                $minutes = rand($typeConfig['duration_min'], $typeConfig['duration_max']);
                $endDate->addMinutes($minutes);
            }

            // Pick a title
            $titleBase = $titles[$typeKey][array_rand($titles[$typeKey])];
            
            // Hijri Mock (Simple string since we don't have the converter in seeder)
            // In real app, your Model observer will fix this on save/update
            $hijriMock = "1447 هـ"; 

            $events[] = [
                'user_id'        => $adminId,
                'target_user_id' => $targetUserId,
                'title'          => $titleBase,
                'start_date'     => $startDate->format('Y-m-d H:i:s'),
                'end_date'       => $endDate->format('Y-m-d H:i:s'),
                'year'           => $year,
                'type'           => $typeKey,
                'bg_color'       => $typeConfig['color'],
                'program'        => rand(0, 1) ? 'مبادرة تطوير' : null,
                'notes'          => rand(0, 1) ? 'يرجى التحضير مسبقاً لهذا النشاط.' : null,
                'is_public'      => rand(0, 10) > 3, // 70% chance public
                'hijri_date'     => $hijriMock,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        // 3. Bulk Insert (Faster than Create loop)
        // Note: This bypasses Model Events, so Observers won't run. 
        // Logic checks (conflicts) are bypassed here to ensure data exists.
        DB::table('calendar_events')->insert($events);
        
        $this->command->info('Successfully seeded 100 calendar events!');
    }
}