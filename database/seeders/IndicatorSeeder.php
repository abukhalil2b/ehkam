<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Governorate;
use App\Models\Wilayat;
use App\Models\GuidanceStatistic;
use App\Models\QuranSchoolStatistic;
use App\Models\Endowment;
use App\Models\EndowmentStatistic;

class IndicatorSeeder extends Seeder
{
    public function run(): void
    {
        $years = [2022, 2023, 2024, 2025];
        $wilayats = Wilayat::all();
        $governorates = Governorate::all();

        // 1. توليد بيانات الوعظ ومدارس القرآن لكل ولاية
        foreach ($wilayats as $wilayat) {
            foreach ($years as $year) {
                // بيانات الوعظ والإرشاد
                GuidanceStatistic::updateOrCreate([
                    'governorate_id' => $wilayat->governorate_id,
                    'wilayat_id' => $wilayat->id,
                    'year' => $year,
                ], [
                    'imams_and_preachers_count' => rand(10, 50),
                    'muezzins_count' => rand(10, 40),
                    'mentors_male' => rand(0, 5),
                    'mentors_female' => rand(0, 5),
                    'preachers_male' => rand(2, 10),
                    'preachers_female' => rand(2, 10),
                    'religious_guides_male' => rand(1, 8),
                    'religious_guides_female' => rand(1, 8),
                    'supervisors_male' => rand(0, 3),
                    'supervisors_female' => rand(0, 3),
                ]);

                // بيانات مدارس القرآن الكريم
                QuranSchoolStatistic::updateOrCreate([
                    'governorate_id' => $wilayat->governorate_id,
                    'wilayat_id' => $wilayat->id,
                    'year' => $year,
                ], [
                    'traditional_schools_count' => rand(1, 15),
                    'traditional_classes_count' => rand(5, 30),
                    'traditional_teachers_count' => rand(2, 20),
                    'supervisors_count' => rand(1, 5),
                    'distance_classes_count' => rand(2, 10),
                    'distance_teachers_count' => rand(1, 10),
                    'distance_semesters_count' => rand(1, 3),
                ]);
            }
        }

        // 2. توليد بيانات المؤسسات الوقفية
        $endowmentTypes = ['عامة', 'خاصة'];
        foreach ($governorates as $governorate) {
            // ننشئ 3 مؤسسات وقفية افتراضية لكل محافظة
            for ($i = 1; $i <= 3; $i++) {
                $endowment = Endowment::firstOrCreate([
                    'name' => 'مؤسسة وقف ' . $governorate->name . ' الأهلية رقم ' . $i,
                    'governorate_id' => $governorate->id,
                ], [
                    'type' => $endowmentTypes[array_rand($endowmentTypes)],
                ]);

                // توليد إيرادات ومصروفات لكل مؤسسة عبر السنوات الـ 4
                foreach ($years as $year) {
                    EndowmentStatistic::updateOrCreate([
                        'endowment_id' => $endowment->id,
                        'year' => $year,
                    ], [
                        'employees_count' => rand(5, 30),
                        'revenues' => rand(5000, 50000) + (rand(0, 99) / 100), // مبالغ عشوائية مع كسور
                        'expenses' => rand(2000, 40000) + (rand(0, 99) / 100),
                    ]);
                }
            }
        }
    }
}
