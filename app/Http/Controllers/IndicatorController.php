<?php

namespace App\Http\Controllers;

use App\Models\IndicatorAchievement;
use App\Models\IndicatorTarget;
use App\Models\Indicator;
use App\Models\PeriodTemplate;
use App\Models\Sector;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IndicatorController extends Controller
{


    public function target(Indicator $indicator, Request $request)
    {
        $current_year = now()->year;

        // القطاعات المرتبطة بالمؤشر
        $sectors = $indicator->sectorsCollection;

        // جلب الفترات حسب نوع الدورة
        $periods = PeriodTemplate::where('cate', $indicator->period)
            ->orderBy('id')
            ->get();

        // جلب المستهدفات الموجودة بالفعل
        $existingTargets = IndicatorTarget::where('indicator_id', $indicator->id)
            ->where('year', $current_year)
            ->get()
            ->groupBy('sector_id'); // الآن كل قطاع يحتوي على مجموعة من الـ periods

        // القطاع المحدد للاستخدام في الـ View
        $sectorId = $request->get('sector_id');

        $targets = [];
        if ($sectorId) {
            $targets = IndicatorTarget::where('indicator_id', $indicator->id)
                ->where('sector_id', $sectorId)
                ->where('year', $current_year)
                ->get()
                ->keyBy('period_index');
        }

        return view('indicator.target', compact(
            'indicator',
            'current_year',
            'sectors',
            'periods',
            'existingTargets',
            'sectorId',
            'targets'
        ));
    }


    public function storeTarget(Request $request, Indicator $indicator)
    {
        $current_year = $request->input('year');
        $sectorId = $request->input('sector_id');
        $values = $request->input('values', []);

        foreach ($values as $periodId => $value) {
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
        $current_year = date('Y');
        $last_year = $current_year - 1;

        $indicators = Indicator::where('current_year', $current_year)->get();

        // Check if there is data to copy from last year
        $has_previous_data = Indicator::where('current_year', $last_year)->exists();

        return view('indicator.index', compact('indicators', 'current_year', 'has_previous_data', 'last_year'));
    }


    public function show(Indicator $indicator)
    {
        // 1. Get the names of the associated sectors
        // 1. Get the names of the associated sectors
        // Assuming the 'sectors' column stores a JSON array of Sector IDs
        $sectorsData = $indicator->sectors;
        $selectedSectorIds = is_string($sectorsData) ? json_decode($sectorsData, true) : $sectorsData;
        $selectedSectorIds = is_array($selectedSectorIds) ? $selectedSectorIds : [];
        $selectedSectors = Sector::whereIn('id', $selectedSectorIds)->pluck('name')->toArray();

        // 3. Get any sub-indicators (children)
        // These are indicators where the parent_id is the current indicator's ID
        $subIndicators = Indicator::where('parent_id', $indicator->id)->get(['id', 'title', 'code']);

        return view('indicator.show', compact('indicator', 'selectedSectors', 'subIndicators'));
    }

    public function create()
    {
        $sectors = Sector::all();

        return view('indicator.create', compact('sectors'));
    }

    private function getMonthMap()
    {
        // Centralized map for Arabic month names
        return [
            'يناير' => 'January',
            'فبراير' => 'February',
            'مارس' => 'March',
            'أبريل' => 'April',
            'مايو' => 'May',
            'يونيو' => 'June',
            'يوليو' => 'July',
            'أغسطس' => 'August',
            'سبتمبر' => 'September',
            'أكتوبر' => 'October',
            'نوفمبر' => 'November',
            'ديسمبر' => 'December',
        ];
    }

    private function validateAndPrepareData(Request $request)
    {
        $validated = $request->validate([
            'target_for_indicator' => 'nullable|numeric',
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
            'unit' => 'nullable|string|max:255',
            'formula' => 'nullable|string|max:255',
            'first_observation_date' => 'nullable|date',
            'baseline_formula' => 'nullable|string',
            'baseline_after_application' => 'nullable|string|max:255',
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

    /**
     * Show the form for editing the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Indicator $indicator)
    {
        $validatedData = $this->validateAndPrepareData($request);

        $indicator->update($validatedData);

        return redirect()->route('admin_setting.indicator.index', $indicator->current_year)->with('success', ' تم تحديث المؤشر بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Indicator $indicator)
    {
        $indicator->delete();

        return redirect()->route('indicator.index')->with('success', ' تم حذف المؤشر بنجاح.');
    }
}
