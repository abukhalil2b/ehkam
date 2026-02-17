<?php

namespace App\Http\Controllers;

use App\Models\IndicatorAchievement;
use App\Models\IndicatorTarget;
use App\Models\Indicator;
use App\Models\PeriodTemplate;
use App\Models\Sector;
use App\Services\IndicatorTargetCalculationService;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{


    public function editSectors(Indicator $indicator)
    {
        // جلب جميع القطاعات المتاحة في النظام
        $allSectors = Sector::all();

        // جلب أرقام (IDs) القطاعات المرتبطة حالياً بهذا المؤشر
        $currentSectorIds = $indicator->sectors_with_baseline->pluck('id')->toArray();

        return view('indicator.link_sectors', compact('indicator', 'allSectors', 'currentSectorIds'));
    }

    public function updateSectors(Request $request, Indicator $indicator)
    {
        // استلام المصفوفة من الـ Checkboxes
        $sectorIds = $request->input('sectors', []);

        /*
    * استخدام sync بدون حذف البيانات السابقة في الجدول الوسيط
    * سنستخدم syncWithoutDetaching أو sync مع الحفاظ على قيم الـ baseline
    * الأفضل هنا استخدام sync لأنه يسمح بإزالة القطاعات التي تم إلغاء تحديدها
    */

        // ملاحظة: sync سيحذف القطاعات غير الموجودة في المصفوفة. 
        // إذا أردت الحفاظ على قيم baseline للقطاعات القديمة، سنقوم بعملية ذكية:
        $indicator->sectors_with_baseline()->sync($sectorIds);

        return redirect()->route('indicator.baselines.edit', $indicator)
            ->with('success', 'تم ربط القطاعات بنجاح، يرجى الآن تحديد خطوط الأساس لها.');
    }
    public function editBaselines(Indicator $indicator)
    {
        // جلب القطاعات المرتبطة بالمؤشر مع بيانات الجدول الوسيط
        $indicator->load('sectors_with_baseline');

        return view('indicator.baselines_edit', compact('indicator'));
    }

    public function updateBaselines(Request $request, Indicator $indicator)
    {
        $baselines = $request->input('baselines');

        foreach ($baselines as $sectorId => $data) {
            // تحديث بيانات الجدول الوسيط (الربط بين المؤشر والقطاع)
            $indicator->sectors_with_baseline()->updateExistingPivot($sectorId, [
                'baseline_numeric' => $data['value'],
                'baseline_year'    => $data['year'],
            ]);
        }

        return redirect()->route('indicator.show', $indicator)
            ->with('success', 'تم تحديث خطوط الأساس للقطاعات بنجاح');
    }

    public function target(Indicator $indicator, Request $request)
    {
        $currentYear = now()->year;
        $lastYear    = $currentYear - 1;

        // القطاعات
        $sectors = $indicator->sectorsCollection;

        // الفترات
        switch ($indicator->period) {
            case 'quarterly':
                $periods = [1, 2, 3, 4];
                break;
            case 'half_yearly':
                $periods = [1, 2];
                break;
            case 'monthly':
                $periods = range(1, 12);
                break;
            default: // annually
                $periods = [1];
        }

        // القطاع المختار
        $sectorId = $request->get('sector_id');

        // مستهدفات القطاع للسنة الحالية
        $targets = collect();
        if ($sectorId) {
            $targets = IndicatorTarget::where('indicator_id', $indicator->id)
                ->where('sector_id', $sectorId)
                ->where('year', $currentYear)
                ->get()
                ->keyBy('period_index');
        }

        // إنجاز السنة الماضية (سنوي)
        $achievement = IndicatorAchievement::where('indicator_id', $indicator->id)
            ->where('sector_id', $sectorId)
            ->where('year', $lastYear)
            ->whereNull('period_index')
            ->first();

        // المستهدف العام (بدون قطاع)
        $publicTarget = IndicatorTarget::where('indicator_id', $indicator->id)
            ->where('year', $currentYear)
            ->whereNull('sector_id')
            ->first();

        /**
         * حساب مستهدف القطاع
         * القاعدة:
         * previous_value + (previous_value * percentage / 100)
         */
        $sectorTarget = null;

        if ($achievement && $publicTarget) {
            $sectorTarget = $achievement->achieved_value *
                (1 + ($publicTarget->target_value / 100));
        }

        // fallback في حال عدم وجود بيانات
        if (!$sectorTarget && $indicator->baseline_numeric) {
            $sectorTarget = $indicator->baseline_numeric;
        }

        return view('indicator.target', compact(
            'indicator',
            'currentYear',
            'sectors',
            'periods',
            'sectorId',
            'sectorTarget',
            'targets'
        ));
    }


    public function storeTarget(Request $request, Indicator $indicator)
    {


        $current_year = $request->input('year');
        $sectorId = $request->input('sector_id');
        $values = $request->input('values', []);

        foreach ($values as $periodId => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            IndicatorTarget::updateOrCreate(
                [
                    'indicator_id' => $indicator->id,
                    'sector_id' => $sectorId,
                    'year' => $current_year,
                    'period_index' => $periodId,
                ],
                [
                    'target_value' => $value ?: 0,
                ]
            );
        }

        return back()->with('success', 'تم حفظ المستهدفات بنجاح');
    }

    public function achieved(Indicator $indicator)
    {
        $current_year = date('Y');
        $sectorsData = $indicator->sectors;
        $selectedSectorIds = is_string($sectorsData) ? json_decode($sectorsData, true) : $sectorsData;
        $selectedSectorIds = is_array($selectedSectorIds) ? $selectedSectorIds : [];
        $sectors = Sector::whereIn('id', $selectedSectorIds)->get();
        $periods = PeriodTemplate::where('cate', $indicator->period)->get();

        // Load targets for context
        $targets = IndicatorTarget::where('indicator_id', $indicator->id)
            ->where('year', $current_year)
            ->get()
            ->keyBy(function ($item) {
                return $item->sector_id . '-' . $item->period_index;
            });

        // Load achievements
        $achievements = IndicatorAchievement::where('indicator_id', $indicator->id)
            ->where('year', $current_year)
            ->get()
            ->keyBy(function ($item) {
                return $item->sector_id . '-' . $item->period_index;
            });

        return view('indicator.achieved', compact('indicator', 'current_year', 'sectors', 'periods', 'targets', 'achievements'));
    }

    public function storeAchieved(Request $request, Indicator $indicator)
    {
        $validated = $request->validate([
            'year' => 'required|integer',
            'achievements' => 'array',
            'achievements.*.sector_id' => 'required|exists:sectors,id',
            'achievements.*.period_index' => 'required|integer',
            'achievements.*.value' => 'nullable|numeric|min:0',
            'achievements.*.notes' => 'nullable|string',
        ]);

        foreach ($validated['achievements'] ?? [] as $data) {
            IndicatorAchievement::updateOrCreate(
                [
                    'indicator_id' => $indicator->id,
                    'sector_id' => $data['sector_id'],
                    'year' => $validated['year'],
                    'period_index' => $data['period_index'],
                ],
                [
                    'achieved_value' => $data['value'] ?? 0,
                    'notes' => $data['notes'] ?? null,
                ]
            );
        }

        return back()->with('success', 'تم حفظ النتائج المحققة بنجاح');
    }

    public function index()
    {

        $indicators = Indicator::all();

        return view('indicator.index', compact('indicators'));
    }


    public function show(Indicator $indicator, IndicatorTargetCalculationService $service)
    {
        $calculatedTargets = $service->calculateTargets($indicator, true);

        $sectorRanking = $service->getSectorsRanking($indicator, now()->year);
        return view('indicator.show', compact('calculatedTargets', 'indicator', 'sectorRanking'));
    }



    public function create()
    {
        $sectors = Sector::all();

        return view('indicator.create', compact('sectors'));
    }



    private function validateAndPrepareData(Request $request)
    {
        $validated = $request->validate([
            'main_criteria' => 'nullable|string',
            'is_main' => 'required|numeric',
            'sub_criteria' => 'nullable|string',
            'code' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'owner' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'measurement_tool' => 'nullable|string',
            'polarity' => 'nullable|string|max:255',
            'polarity_description' => 'nullable|string',
            'unit' => 'nullable|in:percentage,number',
            'formula' => 'nullable|string|max:255',
            'first_observation_date' => 'nullable|date',
            'baseline_formula' => 'nullable|string',
            'baseline_numeric' => 'nullable|numeric|min:0|max:1000000000',
            'baseline_year' => 'nullable|numeric|min:2022|max:2040',
            'survey_question' => 'nullable|string',
            'proposed_initiatives' => 'nullable|string',
            'period' => 'required|string|max:11',
            'sectors' => 'nullable|array',
            'parent_id' => 'nullable|exists:indicators,id',
        ]);

        // --- Date Handling (Simplified) ---
        // The value is already in 'YYYY-MM-DD' format if valid, so no complex parsing is needed.
        // We ensure it is cast to a string or null if empty.
        $validated['first_observation_date'] = $validated['first_observation_date'] ?? null;

        // --- Sector Array Handling ---
        // $validated['sectors'] = json_encode($validated['sectors'] ?? []);
        // FIX: Do not json_encode manually if the model casts 'sectors' => 'array'
        $validated['sectors'] = $validated['sectors'] ?? [];

        return $validated;
    }

    public function store(Request $request)
    {
        // return $request->all();
        $validatedData = $this->validateAndPrepareData($request);

        Indicator::create($validatedData);

        return redirect()->route('indicator.index')->with('success', ' تم اضافة المؤشر بنجاح.');
    }

    public function edit(Indicator $indicator)
    {
        $c1Sectors = Sector::where('cate', 1)->get();
        $c2Sectors = Sector::where('cate', 2)->get();

        // Decode the stored sector IDs for the multi-select field
        $sectorsData = $indicator->sectors;
        $selectedSectorIds = is_string($sectorsData) ? json_decode($sectorsData, true) : $sectorsData;
        $selectedSectorIds = is_array($selectedSectorIds) ? $selectedSectorIds : [];

        $periodOptions = [
            'annually' => 'سنوي',
            'half_yearly' => 'نصف سنوي',
            'quarterly' => 'ربع سنوي',
            'monthly' => 'شهري',
        ];

        return view('indicator.edit', compact(
            'indicator',
            'c1Sectors',
            'c2Sectors',
            'selectedSectorIds',
            'periodOptions'
        ));
    }

    public function update(Request $request, Indicator $indicator)
    {
        $validatedData = $this->validateAndPrepareData($request);

        $indicator->update($validatedData);

        return redirect()->route('admin_setting.indicator.index')->with('success', ' تم تحديث المؤشر بنجاح.');
    }

    public function destroy(Indicator $indicator)
    {
        $indicator->delete();

        return redirect()->route('indicator.index')->with('success', ' تم حذف المؤشر بنجاح.');
    }
}
