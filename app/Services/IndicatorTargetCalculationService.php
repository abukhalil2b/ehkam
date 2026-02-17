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
            // نحدد دالة التجميع: المتوسط للنسب المئوية والمجموع للأرقام المطلقة
            $aggregateFunction = ($indicator->unit === 'percentage') ? 'AVG(achieved_value)' : 'SUM(achieved_value)';

            // جلب البيانات مجمعة حسب السنة و (جهة الإدخال)
            // هذا يسمح لنا بمعرفة ما إذا كان هناك رقم معتمد للمؤشر أو مجرد محققات قطاعات
            $actualsByYear = $indicator->achieved()
                ->select('year', 'achieved_by', DB::raw("$aggregateFunction as value"))
                ->groupBy('year', 'achieved_by')
                ->get()
                ->groupBy('year');
        }

        $currentValue = (float) ($indicator->baseline_numeric ?? 0);
        $results = [];

        foreach ($targets as $target) {
            // 3. حساب المستهدف التراكمي بناءً على نوع المؤشر (نسبة vs رقم)
            if ($target->year > $indicator->baseline_year) {
                if ($indicator->unit === 'percentage') {
                    // نمو مركب (تراكمي) مع ضمان عدم تجاوز سقف الـ 100%
                    $currentValue = min(100, $currentValue * (1 + ($target->target_value / 100)));
                } else {
                    // زيادة عددية بسيطة للمؤشرات الرقمية
                    $currentValue = $currentValue + $target->target_value;
                }
            } else {
                // السنة التي تساوي أو تسبق سنة الأساس تعود لقيمة الأساس الأصلية
                $currentValue = (float) $indicator->baseline_numeric;
            }

            // 4. تحديد "المحقق الفعلي" بناءً على قانون الأولوية:
            // الأولوية 1: السجل الذي تم إدخاله للمؤشر مباشرة (achieved_by = indicator)
            // الأولوية 2: مجموع/متوسط ما حققته القطاعات (achieved_by = sector)
            $yearData = $actualsByYear->get($target->year);

            $actualValue = null;
            $dataSource = null;

            if ($yearData) {
                $officialRecord = $yearData->where('achieved_by', 'indicator')->first();
                $sectorRecord = $yearData->where('achieved_by', 'sector')->first();

                if ($officialRecord) {
                    $actualValue = $officialRecord->value;
                    $dataSource = 'official'; // رقم معتمد من الوزارة
                } elseif ($sectorRecord) {
                    $actualValue = $sectorRecord->value;
                    $dataSource = 'aggregated'; // رقم تجميعي من القطاعات
                }
            }

            // 5. بناء مصفوفة النتائج النهائية
            $results[] = [
                'year'              => $target->year,
                'target_increment'  => $target->target_value,
                'calculated_target' => $currentValue,
                'actual_value'      => $actualValue,
                'data_source'       => $dataSource, // يفيدك في الـ Blade لإظهار "أيقونة" توضح مصدر الرقم
                'performance'       => ($actualValue && $currentValue > 0) ? ($actualValue / $currentValue) * 100 : null,
            ];
        }

        return $results;
    }

    public function getSectorsRanking(Indicator $indicator, $year): \Illuminate\Support\Collection
    {
        // 1. جلب نسبة النمو المستهدفة لهذه السنة من جدول indicator_targets
        $targetRecord = $indicator->targets()
            ->where('year', $year)
            ->where('target_for', 'indicator') // نأخذ النسبة العامة المطبقة على الجميع
            ->first();

        $growthRate = $targetRecord ? $targetRecord->target_value : 0;

        // 2. جلب القطاعات المرتبطة مع خطوط الأساس الخاصة بها
        // نفترض أن العلاقة تسمى sectors في موديل Indicator وتستخدم الجدول الوسيط indicator_sector
        return $indicator->sectors_with_baseline->map(function ($sector) use ($indicator, $year, $growthRate) {

            // حساب مستهدف القطاع بناءً على خط أساسه الخاص
            $sectorBaseline = (float) $sector->pivot->baseline_numeric;

            // ملاحظة: للتبسيط هنا نحسب النمو لسنة واحدة، إذا أردت النمو التراكمي لعدة سنوات
            // يمكنك استدعاء منطق النمو التراكمي بناءً على الفرق بين $year و baseline_year
            $yearsDiff = $year - $sector->pivot->baseline_year;
            $sectorTarget = $sectorBaseline;

            for ($i = 0; $i < $yearsDiff; $i++) {
                $sectorTarget = ($indicator->unit === 'percentage')
                    ? $sectorTarget * (1 + ($growthRate / 100))
                    : $sectorTarget + $growthRate;
            }

            // جلب المحقق لهذا القطاع في هذه السنة (مجموع الأرباع)
            $aggregateFunction = ($indicator->unit === 'percentage') ? 'avg' : 'sum';
            $actualValue = $indicator->achieved()
                ->where('sector_id', $sector->id)
                ->where('year', $year)
                ->$aggregateFunction('achieved_value');

            $performance = ($actualValue && $sectorTarget > 0) ? ($actualValue / $sectorTarget) * 100 : 0;

            return [
                'name' => $sector->name,
                'target' => $sectorTarget,
                'actual' => $actualValue,
                'performance' => $performance,
            ];
        })->sortByDesc('performance'); // الترتيب من الأعلى إنجازاً للأقل
    }
}
