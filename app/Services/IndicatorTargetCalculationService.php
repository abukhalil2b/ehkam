<?php

namespace App\Services;

use App\Models\Indicator;
use Illuminate\Support\Facades\DB;

class IndicatorTargetCalculationService
{
    public function calculateTargets(Indicator $indicator, $includeActuals = true): array
    {
        // 1. جلب المستهدفات السنوية المرتبطة بالمؤشر العام فقط
        $targets = $indicator->targets()
            ->where('target_for', 'indicator')
            ->orderBy('year')
            ->get();

        // 2. التحقق من وجود بيانات محققة ودمجها حسب الأولوية
        $actualsByYear = collect();
        if ($includeActuals) {
            $aggregateFunction = ($indicator->unit === 'percentage') ? 'AVG(achieved_value)' : 'SUM(achieved_value)';

            $actualsByYear = $indicator->achieved()
                ->select('year', 'achieved_by', DB::raw("$aggregateFunction as value"))
                ->groupBy('year', 'achieved_by')
                ->get()
                ->groupBy('year');
        }

        $currentValue = (float) ($indicator->baseline_numeric ?? 0);
        $results = [];

        foreach ($targets as $target) {
            // 3. حساب المستهدف التراكمي (النمو المركب لجميع أنواع المؤشرات)
            if ($target->year > $indicator->baseline_year) {
                
                // تطبيق النمو المركب 
                $currentValue = $currentValue * (1 + ($target->target_value / 100));

                // إذا كان المؤشر نسبة مئوية، نضمن ألا يتجاوز السقف 100%
                if ($indicator->unit === 'percentage') {
                    $currentValue = min(100, $currentValue);
                }

            } else {
                // السنة التي تساوي أو تسبق سنة الأساس تعود لقيمة الأساس الأصلية
                $currentValue = (float) $indicator->baseline_numeric;
            }

                        // الأولوية 1: السجل الذي تم إدخاله للمؤشر مباشرة (achieved_by = indicator)
            // الأولوية 2: مجموع/متوسط ما حققته القطاعات (achieved_by = sector)
            // 4. تحديد المحقق الفعلي بناءً على الأولوية
            $yearData = $actualsByYear->get($target->year);

            $actualValue = null;
            $dataSource = null;

            if ($yearData) {
                $officialRecord = $yearData->where('achieved_by', 'indicator')->first();
                $sectorRecord = $yearData->where('achieved_by', 'sector')->first();

                if ($officialRecord) {
                    $actualValue = $officialRecord->value;
                    $dataSource = 'official';  // رقم معتمد من الوزارة
                } elseif ($sectorRecord) {
                    $actualValue = $sectorRecord->value;
                    $dataSource = 'aggregated';  // رقم تجميعي من القطاعات
                }
            }

            // 5. بناء مصفوفة النتائج النهائية
            $results[] = [
                'year'              => $target->year,
                'target_increment'  => $target->target_value,
                'calculated_target' => $currentValue,
                'actual_value'      => $actualValue,
                'data_source'       => $dataSource, 
                'performance'       => ($actualValue !== null && $currentValue > 0) ? ($actualValue / $currentValue) * 100 : null,
            ];
        }

        return $results;
    }

    public function getSectorsRanking(Indicator $indicator, $year): \Illuminate\Support\Collection
    {
        // 1. جلب المستهدفات بشكل متسلسل من سنة الأساس وحتى السنة المطلوبة
        // هذا يضمن حساب النمو التراكمي الصحيح حتى لو اختلفت النسبة من سنة لأخرى
        $targets = $indicator->targets()
            ->where('target_for', 'indicator')
            ->where('year', '>', $indicator->baseline_year)
            ->where('year', '<=', $year)
            ->orderBy('year')
            ->get();

        
        return $indicator->sectors_with_baseline->map(function ($sector) use ($indicator, $year, $targets) {

            $sectorBaseline = (float) $sector->pivot->baseline_numeric;
            $sectorTarget = $sectorBaseline;

            // 3. تطبيق النمو المركب تراكمياً على خط أساس القطاع
            foreach ($targets as $target) {
                // نتأكد أننا نحسب النمو للسنوات التي تلي خط أساس القطاع تحديداً
                if ($target->year > $sector->pivot->baseline_year) {
                    $sectorTarget = $sectorTarget * (1 + ($target->target_value / 100));
                    
                    if ($indicator->unit === 'percentage') {
                        $sectorTarget = min(100, $sectorTarget);
                    }
                }
            }

            // 4. جلب المحقق لهذا القطاع في السنة المطلوبة
            $aggregateFunction = ($indicator->unit === 'percentage') ? 'avg' : 'sum';
            $actualValue = $indicator->achieved()
                ->where('sector_id', $sector->id)
                ->where('year', $year)
                ->$aggregateFunction('achieved_value');

            $performance = ($actualValue !== null && $sectorTarget > 0) ? ($actualValue / $sectorTarget) * 100 : 0;

            return [
                'name' => $sector->name,
                'target' => $sectorTarget,
                'actual' => $actualValue,
                'performance' => $performance,
            ];
        })->sortByDesc('performance');
    }
}
