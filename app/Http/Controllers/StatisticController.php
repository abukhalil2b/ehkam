<?php

namespace App\Http\Controllers;

use App\Models\KpiIndicator;
use App\Models\KpiValue;
use App\Models\Sector;
use Illuminate\Http\Request;

class StatisticController extends Controller
{

    public function index()
    {
        return view('statistic.index');
    }

    public function quran($id)
    {
        return view("statistic.quran.{$id}");
    }

    public function zakah($id)
    {
        return view("statistic.zakah.{$id}");
    }
    
    /**
     * عرض صفحة بطاقة الأداء المتوازن
     */
    public function bsc()
    {
        $indicators = KpiIndicator::active()
            ->ordered()
            ->with(['values' => function ($query) {
                $query->orderBy('year')->orderBy('quarter');
            }])
            ->get();

        // تحويل البيانات للشكل المطلوب في الواجهة
        $kpis = $indicators->map(function ($indicator) {
            $data = [
                'id' => $indicator->id,
                'code' => $indicator->code,
                'title' => $indicator->title,
                'unit' => $indicator->unit,
                'currency' => $indicator->currency,
                'category' => $indicator->category,
            ];

            // تجميع القيم حسب السنة
            foreach ([2023, 2024, 2025] as $year) {
                $yearValues = $indicator->values->where('year', $year);
                $data["data{$year}"] = [
                    'target' => $yearValues->pluck('target_value')->map(fn($v) => (float) $v)->values()->toArray(),
                    'actual' => $yearValues->pluck('actual_value')->map(fn($v) => (float) $v)->values()->toArray(),
                ];
                
                // التأكد من وجود 4 أرباع
                if (count($data["data{$year}"]['target']) < 4) {
                    $data["data{$year}"]['target'] = array_pad($data["data{$year}"]['target'], 4, 0);
                    $data["data{$year}"]['actual'] = array_pad($data["data{$year}"]['actual'], 4, 0);
                }
            }

            // المبررات (من آخر ربع)
            $lastValue = $indicator->values->last();
            $data['justification'] = $lastValue?->justification ?? '';

            return $data;
        });

        $years = [2023, 2024, 2025];

        return view('statistic.bsc', compact('kpis', 'years'));
    }

    /**
     * تحديث قيمة مؤشر (AJAX)
     */
    public function updateKpiValue(Request $request)
    {
        $request->validate([
            'indicator_id' => 'required|exists:kpi_indicators,id',
            'year' => 'required|integer|min:2020|max:2030',
            'quarter' => 'required|integer|min:1|max:4',
            'target_value' => 'nullable|numeric|min:0',
            'actual_value' => 'nullable|numeric|min:0',
        ]);

        $value = KpiValue::updateOrCreate(
            [
                'kpi_indicator_id' => $request->indicator_id,
                'year' => $request->year,
                'quarter' => $request->quarter,
            ],
            [
                'target_value' => $request->target_value ?? 0,
                'actual_value' => $request->actual_value ?? 0,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ القيمة بنجاح',
            'data' => $value,
        ]);
    }

    /**
     * تحديث مبررات مؤشر (AJAX)
     */
    public function updateKpiJustification(Request $request)
    {
        $request->validate([
            'indicator_id' => 'required|exists:kpi_indicators,id',
            'justification' => 'nullable|string|max:2000',
        ]);

        // حفظ المبررات في آخر ربع موجود أو إنشاء واحد جديد
        $value = KpiValue::where('kpi_indicator_id', $request->indicator_id)
            ->orderBy('year', 'desc')
            ->orderBy('quarter', 'desc')
            ->first();

        if ($value) {
            $value->update(['justification' => $request->justification]);
        } else {
            // إنشاء قيمة جديدة للربع الحالي
            $currentQuarter = ceil(now()->month / 3);
            KpiValue::create([
                'kpi_indicator_id' => $request->indicator_id,
                'year' => now()->year,
                'quarter' => $currentQuarter,
                'target_value' => 0,
                'actual_value' => 0,
                'justification' => $request->justification,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ المبررات بنجاح',
        ]);
    }

    /**
     * جلب بيانات المؤشرات (API)
     */
    public function getKpiData()
    {
        $indicators = KpiIndicator::active()
            ->ordered()
            ->with(['values' => function ($query) {
                $query->orderBy('year')->orderBy('quarter');
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $indicators,
        ]);
    }
}
