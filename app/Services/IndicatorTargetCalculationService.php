<?php

namespace App\Services;

use App\Models\Indicator;
use Illuminate\Support\Facades\DB;

class IndicatorTargetCalculationService
{
    public function calculateTargets(Indicator $indicator, $includeActuals = true): array
    {
        if (!$indicator->baseline_year) {
            abort(403, 'لابد من تحديد سنة الأساس');
        }

        $targets = $indicator->targets()
            ->where('target_for', 'indicator')
            ->where('year', '>', $indicator->baseline_year)
            ->orderBy('year')
            ->get();

        $actualsByYear = collect();
        if ($includeActuals) {
            // بما أن المحقق يأتي من القطاعات فقط، نقوم بالتجميع مباشرة
            $aggregateFunction = ($indicator->unit === 'percentage') ? 'AVG(achieved_value)' : 'SUM(achieved_value)';

            $actualsByYear = $indicator->achieved()
                ->select('year', DB::raw("$aggregateFunction as value"))
                ->groupBy('year')
                ->get()
                ->keyBy('year');
        }

        $currentValue = (float) ($indicator->baseline_numeric ?? 0);
        $results = [];

        foreach ($targets as $target) {
            // التفريق بين النسبة والرقم في الحساب التراكمي
            if ($target->unit === 'percentage') {
                $currentValue = $currentValue * (1 + ($target->target_value / 100));
                if ($indicator->unit === 'percentage') {
                    $currentValue = min(100, $currentValue); // السقف 100%
                }
            } else {
                // إذا كان المستهدف عبارة عن رقم ثابت يضاف سنوياً
                $currentValue += $target->target_value;
            }

            $yearData = $actualsByYear->get($target->year);
            $actualValue = $yearData ? (float) $yearData->value : null;

            $results[] = [
                'year'              => $target->year,
                'target_increment'  => $target->target_value,
                'calculated_target' => $currentValue,
                'actual_value'      => $actualValue,
                'data_source'       => 'aggregated_sectors', // المصدر دائماً تجميعي الآن
                'performance'       => ($actualValue !== null && $currentValue > 0) ? ($actualValue / $currentValue) * 100 : null,
            ];
        }

        return $results;
    }

    public function getSectorsRanking(Indicator $indicator, $year): \Illuminate\Support\Collection
    {
        // تحديد دالة التجميع بناءً على نوع المؤشر (المجاميع للأرقام، والمتوسط للنسب)
        $aggregateFunction = ($indicator->unit === 'percentage') ? 'avg' : 'sum';

        return $indicator->sectors->map(function ($sector) use ($indicator, $year, $aggregateFunction) {

            // 1. جلب المستهدف اليدوي للقطاع لهذه السنة
            $sectorTarget = $indicator->targets()
                ->where('target_for', 'sector')
                ->where('sector_id', $sector->id)
                ->where('year', $year)
                ->$aggregateFunction('target_value') ?? 0;

            // 2. جلب المحقق الفعلي للقطاع لهذه السنة
            $actualValue = $indicator->achieved()
                ->where('sector_id', $sector->id)
                ->where('year', $year)
                ->$aggregateFunction('achieved_value') ?? 0;

            // 3. حساب نسبة الإنجاز (الأداء)
            $performance = ($actualValue > 0 && $sectorTarget > 0)
                ? ($actualValue / $sectorTarget) * 100
                : 0;

            return [
                'name'        => $sector->name,
                'target'      => (float) $sectorTarget,
                'actual'      => (float) $actualValue,
                'performance' => $performance,
            ];
        })->sortByDesc('performance')->values(); // values() لإعادة ترتيب مفاتيح المصفوفة
    }
    
}
