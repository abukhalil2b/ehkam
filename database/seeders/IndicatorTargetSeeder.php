<?php

namespace Database\Seeders;

use App\Models\Indicator;
use App\Models\IndicatorTarget;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndicatorTargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = Indicator::all();

        foreach ($indicators as $indicator) {
            // Determine a base target value based on existing data or a random start
            $baseValue = $indicator->baseline_numeric ?? rand(10, 50);

            // If unit is percent, keep it under 100
            if ($indicator->unit == 'percentage' && $baseValue > 100) {
                $baseValue = rand(10, 40);
            }

            for ($year = 2025; $year <= 2040; $year++) {
                // Increment target slightly each year
                $increment = ($year - 2024) * ($indicator->unit == 'percentage' ? 2 : 5);
                $targetValue = $baseValue + $increment;

                // Cap percentage at 100
                if ($indicator->unit == 'percentage' && $targetValue > 100) {
                    $targetValue = 100;
                }

                IndicatorTarget::firstOrCreate(
                    [
                        'indicator_id' => $indicator->id,
                        'year' => $year,
                        'period_index' => 1, // Annual
                    ],
                    [
                        'sector_id' => null, // Assuming general target for now
                        'target_value' => $targetValue,
                        'is_calculated' => false,
                    ]
                );
            }
        }
    }
}
