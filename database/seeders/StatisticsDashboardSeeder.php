<?php

namespace Database\Seeders;

use App\Models\QuranProgram;
use App\Models\QuranStudent;
use App\Models\ZakahStatistic;
use App\Models\QuranSchoolStatistic;
use App\Models\MetricsDictionary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatisticsDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Programs (Quran)
        $programs = [
            ['name' => 'مسار التلاوة', 'type' => 'tilawa'],
            ['name' => 'مسار الحفظ - النبأ', 'type' => 'hifz'],
            ['name' => 'مسار الحفظ - الكهف', 'type' => 'hifz'],
            ['name' => 'مسار الحفظ - الملك', 'type' => 'hifz'],
        ];

        foreach ($programs as $prog) {
            QuranProgram::updateOrCreate(['name' => $prog['name']], $prog);
        }

        $programIds = QuranProgram::pluck('id')->toArray();
        $wilayats = DB::table('wilayats')->pluck('id')->toArray();
        $governorates = DB::table('governorates')->pluck('id')->toArray();

        // Fallback for empty tables
        if (empty($wilayats)) {
            $governorateId = DB::table('governorates')->insertGetId(['name' => 'مسقط']);
            $wilayatId = DB::table('wilayats')->insertGetId(['name' => 'السيب', 'governorate_id' => $governorateId]);
            $wilayats = [$wilayatId];
            $governorates = [$governorateId];
        }

        // 2. Seed Students (Quran)
        $modes = ['traditional', 'distance'];
        $genders = ['male', 'female'];
        $periods = ['morning', 'evening'];
        $semesters = ['first', 'winter', 'second', 'summer'];
        $statuses = ['enrolled', 'graduated', 'dropped'];

        for ($i = 0; $i < 200; $i++) {
            QuranStudent::create([
                'wilayat_id' => $wilayats[array_rand($wilayats)],
                'education_mode' => $modes[array_rand($modes)],
                'gender' => $genders[array_rand($genders)],
                'age' => rand(5, 50),
                'period' => $periods[array_rand($periods)],
                'program_id' => $programIds[array_rand($programIds)],
                'semester' => $semesters[array_rand($semesters)],
                'status' => $statuses[array_rand($statuses)],
                'year' => rand(2023, 2025),
            ]);
        }

        // 3. Seed Zakah Statistics
        foreach ([2023, 2024, 2025] as $year) {
            foreach (array_slice($governorates, 0, 3) as $govId) {
                ZakahStatistic::updateOrCreate(
                    ['governorate_id' => $govId, 'wilayat_id' => null, 'year' => $year],
                    [
                        'annual_target' => 20000,
                        'q1_target' => 5000,
                        'q2_target' => 5000,
                        'q3_target' => 5000,
                        'q4_target' => 5000,
                        'q1_achieved' => rand(3000, 5000),
                        'q2_achieved' => rand(3000, 5000),
                        'q3_achieved' => rand(3000, 5000),
                        'q4_achieved' => rand(3000, 5000),
                        'beneficiary_families_count' => rand(100, 500),
                        'local_committees_count' => rand(5, 20),
                        'electronic_transfers_amount' => rand(5000, 15000),
                    ]
                );
            }
        }

        // 4. Seed Quran School Statistics
        foreach ([2023, 2024, 2025] as $year) {
            foreach (array_slice($governorates, 0, 3) as $govId) {
                QuranSchoolStatistic::updateOrCreate(
                    ['governorate_id' => $govId, 'wilayat_id' => null, 'year' => $year],
                    [
                        'traditional_schools_count' => rand(10, 50),
                        'traditional_classes_count' => rand(20, 100),
                        'traditional_teachers_count' => rand(30, 150),
                        'supervisors_count' => rand(5, 20),
                        'distance_classes_count' => rand(10, 50),
                        'distance_teachers_count' => rand(15, 60),
                        'distance_semesters_count' => 4,
                    ]
                );
            }
        }

        // 5. Seed Metrics Dictionary
        $metrics = [
            ['sector_name' => 'zakah', 'metric_key' => 'zakah_q1', 'name_ar' => 'زكاة الربع الأول', 'data_type' => 'decimal', 'aggregation_type' => 'sum', 'source_table' => 'zakah_statistics', 'source_column' => 'q1_achieved'],
            ['sector_name' => 'quran', 'metric_key' => 'quran_students_total', 'name_ar' => 'إجمالي طلاب القرآن', 'data_type' => 'integer', 'aggregation_type' => 'count', 'source_table' => 'quran_students', 'source_column' => 'id'],
        ];

        foreach ($metrics as $metric) {
            MetricsDictionary::updateOrCreate(['metric_key' => $metric['metric_key']], $metric);
        }
    }
}
