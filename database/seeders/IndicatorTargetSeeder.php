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
        // Years range
        $startYear = 2022;
        $endYear = 2040;
        $targetPercentage = 5; // 5%

        foreach ($indicators as $indicator) {
            foreach (range($startYear, $endYear) as $year) {
                IndicatorTarget::create([
                    'target_for'=>'indicator',
                    'indicator_id' => $indicator->id,
                    'year' => $year,
                    'period_index' => 1, // Annual
                    'target_value' => $targetPercentage, // store **5%**
                    'unit' => 'percentage',
                    'is_calculated' => false,
                ]);
            }
        }
    }
}
