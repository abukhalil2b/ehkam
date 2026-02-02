<?php

namespace App\Http\Controllers;

use App\Models\KpiIndicator;
use App\Models\KpiValue;
use App\Models\KpiReportSetting;
use App\Models\KpiYear;
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

        // الحصول على السنوات من قاعدة البيانات أو استخدام افتراضي
        $years = KpiYear::ordered()->pluck('year')->toArray();
        if (empty($years)) {
            $years = [date('Y')];
        }

        // تحويل البيانات للشكل المطلوب في الواجهة
        $kpis = $indicators->map(function ($indicator) use ($years) {
            $data = [
                'id' => $indicator->id,
                'code' => $indicator->code,
                'title' => $indicator->title,
                'unit' => $indicator->unit,
                'currency' => $indicator->currency,
                'category' => $indicator->category,
            ];

            // تجميع القيم حسب السنة
            foreach ($years as $year) {
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

        return view('statistic.bsc', compact('kpis', 'years'));
    }

    /**
     * عرض صفحة التقارير والرسوم البيانية
     */
    public function bscReport()
    {
        $indicators = KpiIndicator::active()
            ->ordered()
            ->with(['values' => function ($query) {
                $query->orderBy('year')->orderBy('quarter');
            }])
            ->get();

        // الحصول على السنوات من قاعدة البيانات أو استخدام افتراضي
        $years = KpiYear::ordered()->pluck('year')->toArray();
        if (empty($years)) {
            $years = [date('Y')];
        }

        // تحويل البيانات للشكل المطلوب في الواجهة
        $kpis = $indicators->map(function ($indicator) use ($years) {
            $data = [
                'id' => $indicator->id,
                'code' => $indicator->code,
                'title' => $indicator->title,
                'unit' => $indicator->unit,
                'currency' => $indicator->currency,
                'category' => $indicator->category,
            ];

            // تجميع القيم حسب السنة
            foreach ($years as $year) {
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

        return view('statistic.bsc-report', compact('kpis', 'years'));
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

    /**
     * عرض قائمة المؤشرات
     */
    public function kpiIndicators()
    {
        $indicators = KpiIndicator::ordered()->get();

        return view('statistic.kpi.indicators', compact('indicators'));
    }

    /**
     * عرض نموذج إنشاء مؤشر جديد
     */
    public function createKpiIndicator()
    {
        return view('statistic.kpi.indicator-form');
    }

    /**
     * حفظ مؤشر جديد
     */
    public function storeKpiIndicator(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:kpi_indicators,code',
            'title' => 'required|string|max:255',
            'unit' => 'required|string|in:number,percentage,currency',
            'currency' => 'nullable|string|max:10',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        $maxOrder = KpiIndicator::max('display_order') ?? 0;

        KpiIndicator::create([
            'code' => $request->code,
            'title' => $request->title,
            'unit' => $request->unit,
            'currency' => $request->currency,
            'category' => $request->category,
            'description' => $request->description,
            'is_active' => true,
            'display_order' => $maxOrder + 1,
        ]);

        return redirect()->route('statistic.kpi.indicators')
            ->with('success', 'تم إضافة المؤشر بنجاح');
    }

    /**
     * عرض نموذج تعديل مؤشر
     */
    public function editKpiIndicator(KpiIndicator $indicator)
    {
        return view('statistic.kpi.indicator-form', compact('indicator'));
    }

    /**
     * تحديث مؤشر
     */
    public function updateKpiIndicator(Request $request, KpiIndicator $indicator)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:kpi_indicators,code,' . $indicator->id,
            'title' => 'required|string|max:255',
            'unit' => 'required|string|in:number,percentage,currency',
            'currency' => 'nullable|string|max:10',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        $indicator->update([
            'code' => $request->code,
            'title' => $request->title,
            'unit' => $request->unit,
            'currency' => $request->currency,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return redirect()->route('statistic.kpi.indicators')
            ->with('success', 'تم تحديث المؤشر بنجاح');
    }

    /**
     * حذف مؤشر
     */
    public function destroyKpiIndicator(KpiIndicator $indicator)
    {
        // حذف القيم المرتبطة أولاً
        $indicator->values()->delete();
        $indicator->annualReports()->delete();
        $indicator->delete();

        return redirect()->route('statistic.kpi.indicators')
            ->with('success', 'تم حذف المؤشر بنجاح');
    }

    /**
     * عرض صفحة إعدادات التقرير
     */
    public function reportSettings()
    {
        $userId = auth()->id();
        
        // جلب الإعدادات الحالية
        $defaultYears = KpiYear::ordered()->pluck('year')->toArray() ?: [date('Y')];
        $selectedYears = KpiReportSetting::getSetting($userId, 'report_years', $defaultYears);
        $selectedQuarters = KpiReportSetting::getSetting($userId, 'report_quarters', [1, 2, 3, 4]);
        $selectedIndicators = KpiReportSetting::getSetting($userId, 'report_indicators', []);
        
        $indicators = KpiIndicator::ordered()->get();
        $years = KpiYear::ordered()->get();
        $quarters = [1, 2, 3, 4];

        return view('statistic.settings', compact(
            'indicators', 'years', 'quarters',
            'selectedYears', 'selectedQuarters', 'selectedIndicators'
        ));
    }

    /**
     * حفظ إعدادات التقرير
     */
    public function saveReportSettings(Request $request)
    {
        $userId = auth()->id();
        
        $request->validate([
            'years' => 'required|array',
            'years.*' => 'integer|exists:kpi_years,year',
            'quarters' => 'required|array',
            'quarters.*' => 'in:1,2,3,4',
            'indicators' => 'nullable|array',
            'indicators.*' => 'integer|exists:kpi_indicators,id',
        ]);

        // حفظ الإعدادات
        KpiReportSetting::setSetting($userId, 'report_years', $request->years);
        KpiReportSetting::setSetting($userId, 'report_quarters', $request->quarters);
        KpiReportSetting::setSetting($userId, 'report_indicators', $request->indicators ?? []);

        return redirect()->route('statistic.bsc')
            ->with('success', 'تم حفظ الإعدادات بنجاح');
    }

    /**
     * جلب إعدادات التقرير (API)
     */
    public function getReportSettings()
    {
        $userId = auth()->id();
        
        return response()->json([
            'success' => true,
            'data' => [
                'years' => KpiReportSetting::getSetting($userId, 'report_years', 
                    KpiYear::ordered()->pluck('year')->toArray() ?: [date('Y')]),
                'quarters' => KpiReportSetting::getSetting($userId, 'report_quarters', [1, 2, 3, 4]),
                'indicators' => KpiReportSetting::getSetting($userId, 'report_indicators', []),
            ]
        ]);
    }
}
