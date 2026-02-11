<?php

namespace App\Services;

use App\Models\Indicator;
use App\Models\IndicatorTarget;
use Illuminate\Support\Facades\DB;

class IndicatorTargetService
{
    /**
     * Generate targets based on a growth rate (Compound Growth).
     * Example: Baseline 5000, Growth 5% -> 2025=5250, 2026=5512...
     */
    public function generateTargetsByGrowthRate(Indicator $indicator, int $startYear, int $endYear, float $growthPercentage)
    {
        $currentValue = $indicator->baseline_numeric ?? 0;
        
        // Convert percentage (5) to multiplier (1.05)
        $multiplier = 1 + ($growthPercentage / 100);

        DB::transaction(function () use ($indicator, $startYear, $endYear, $multiplier, $currentValue) {
            // Clear existing targets for this range to avoid duplicates
            IndicatorTarget::where('indicator_id', $indicator->id)
                ->whereBetween('year', [$startYear, $endYear])
                ->delete();

            for ($year = $startYear; $year <= $endYear; $year++) {
                // Calculate new value
                $newValue = $currentValue * $multiplier;

                IndicatorTarget::create([
                    'indicator_id' => $indicator->id,
                    'sector_id'    => null, // Or specific sector if needed
                    'year'         => $year,
                    'period_index' => 1, // Default to Annual
                    'target_value' => $newValue,
                    'is_calculated'=> true
                ]);

                // Update current value for next loop (Compound calculation)
                $currentValue = $newValue;
            }
        });
    }

    /**
     * Generate targets by filling gaps between milestones.
     * Example: PDF has 2025=10% and 2030=20%. This fills 2026, 2027, etc.
     */
    public function generateTargetsFromMilestones(Indicator $indicator, array $milestones)
    {
        // Sort years: [2023, 2025, 2030...]
        ksort($milestones);
        
        $years = array_keys($milestones);
        $minYear = min($years);
        $maxYear = max($years);

        DB::transaction(function () use ($indicator, $milestones, $minYear, $maxYear) {
            $lastValue = null;

            for ($year = $minYear; $year <= $maxYear; $year++) {
                // If specific value exists in PDF/Milestones, use it
                if (array_key_exists($year, $milestones)) {
                    $targetValue = $milestones[$year];
                    $isCalculated = false;
                } else {
                    // Otherwise, carry over the last known value (or you could interpolate)
                    $targetValue = $lastValue;
                    $isCalculated = true;
                }

                IndicatorTarget::updateOrCreate(
                    [
                        'indicator_id' => $indicator->id,
                        'year' => $year,
                        'period_index' => 1
                    ],
                    [
                        'target_value' => $targetValue,
                        'is_calculated' => $isCalculated
                    ]
                );

                $lastValue = $targetValue;
            }
        });
    }
}