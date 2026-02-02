<?php

namespace Database\Seeders;

use App\Models\KpiYear;
use Illuminate\Database\Seeder;

class KpiYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [
            [
                'year' => 2023,
                'name' => 'سنة 2023',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'year' => 2024,
                'name' => 'سنة 2024',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'year' => 2025,
                'name' => 'سنة 2025',
                'display_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($years as $year) {
            KpiYear::updateOrCreate(
                ['year' => $year['year']],
                $year
            );
        }

        $this->command->info('KpiYears seeded successfully!');
    }
}
