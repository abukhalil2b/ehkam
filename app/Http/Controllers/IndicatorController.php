<?php

namespace App\Http\Controllers;

use App\Models\IndicatorAchievement;
use App\Models\IndicatorTarget;
use App\Models\Indicator;
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

    public function target(Indicator $indicator, Request $request, IndicatorTargetCalculationService $service)
    {
        $currentYear = $request->integer('year', now()->year);

        // تحميل القطاعات
        $indicator->load('sectors');
        $sectors  = $indicator->sectors;
        $sectorId = $request->integer('sector_id');

        // تحديد الفترات
        $periods = match ($indicator->period) {
            'quarterly'   => [1, 2, 3, 4],
            'half_yearly' => [1, 2],
            'monthly'     => range(1, 12),
            default       => [1],
        };

        $baselineYear = $indicator->baseline_year ?? 2022;

        // 1. حساب جميع المستهدفات (مررنا false لأننا لا نحتاج البيانات المحققة هنا، مما يحسن الأداء)
        $calculatedTargets = $service->calculateTargets($indicator, false);

        // 2. استخراج المستهدف للسنة المطلوبة فقط
        $publicTarget = collect($calculatedTargets)->firstWhere('year', $currentYear);

        // إذا كان هناك قطاع محدد، نجلب إنجازه في هذه السنة، وإلا نجلب الإنجاز التجميعي
        if ($sectorId) {
            $achievementQuery = $indicator->achieved()
                ->where('sector_id', $sectorId)
                ->where('year', $currentYear);
        } else {
            $achievementQuery = $indicator->achieved()
                ->where('year', $currentYear);
        }

        // يمكنك إرجاع المجاميع حسب الفترات أو المجموع الكلي
        $achievement = $achievementQuery->get();

        // جلب مستهدفات القطاع المحدد (إن وجد) لتعبئة الحقول بها
        $sectorTargets = collect();
        if ($sectorId) {
            $sectorTargets = $indicator->targets()
                ->where('target_for', 'sector')
                ->where('sector_id', $sectorId)
                ->where('year', $currentYear)
                ->get()
                ->keyBy('period_index'); // جعل مفتاح المصفوفة هو رقم الفترة لسهولة استدعائه في الـ Blade
        }

        return view('indicator.target', compact(
            'indicator',
            'currentYear',
            'sectors',
            'periods',
            'sectorId',
            'baselineYear',
            'publicTarget',
            'sectorTargets' // أضفنا sectorTargets هنا
        ));
    }

    public function storeSectorTarget(Request $request, Indicator $indicator)
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
                    'sector_id'    => $sectorId,
                    'year'         => $current_year,
                    'period_index' => $periodId,
                    'target_for'   => 'sector',
                ],
                [
                    'target_value' => $value ?: 0,

                    'unit'         => $indicator->unit,
                ]
            );
        }

        return back()->with('success', 'تم حفظ مستهدفات القطاع بنجاح');
    }

    // تأكد من استدعاء النموذج إذا لم يكن مستدعى
    public function achieved(Indicator $indicator, Request $request)
    {
        $currentYear = $request->integer('year', now()->year);

        // تحميل القطاعات المرتبطة بالمؤشر
        $indicator->load('sectors');
        $sectors  = $indicator->sectors;
        $sectorId = $request->integer('sector_id');

        // تحديد الفترات بناءً على دورية المؤشر
        $periods = match ($indicator->period) {
            'quarterly'   => [1, 2, 3, 4],
            'half_yearly' => [1, 2],
            'monthly'     => range(1, 12),
            default       => [1],
        };

        // جلب البيانات المحققة مسبقاً للقطاع المحدد (إن وجد) لتعبئة الحقول للتعديل
        $sectorAchievements = collect();
        if ($sectorId) {
            $sectorAchievements = $indicator->achieved()
                ->where('sector_id', $sectorId)
                ->where('year', $currentYear)
                ->get()
                ->keyBy('period_index'); // جعل رقم الفترة هو المفتاح
        }

        return view('indicator.achieved', compact(
            'indicator',
            'currentYear',
            'sectors',
            'periods',
            'sectorId',
            'sectorAchievements'
        ));
    }

    public function storeAchieved(Request $request, Indicator $indicator)
    {
        // التحقق من المدخلات (اختياري ولكن محبذ)
        $request->validate([
            'year'      => 'required|integer',
            'sector_id' => 'required|exists:sectors,id',
            'values'    => 'array',
            'notes'     => 'array',
        ]);

        $currentYear = $request->input('year');
        $sectorId    = $request->input('sector_id');
        $values      = $request->input('values', []);
        $notes       = $request->input('notes', []);

        foreach ($values as $periodId => $value) {
            // نتجاوز الفترات التي تركها المستخدم فارغة (ولكن نسمح بالصفر 0)
            if ($value === null || $value === '') {
                continue;
            }

            IndicatorAchievement::updateOrCreate(
                [
                    'indicator_id' => $indicator->id,
                    'sector_id'    => $sectorId,
                    'year'         => $currentYear,
                    'period_index' => $periodId,
                ],
                [
                    'achieved_value' => $value,
                    'unit'           => $indicator->unit,
                    'notes'          => $notes[$periodId] ?? null, // حفظ الملاحظة إن وجدت
                ]
            );
        }

        return back()->with('success', 'تم حفظ القيم المحققة للقطاع بنجاح');
    }

    public function index()
    {

        $indicators = Indicator::all();

        return view('indicator.index', compact('indicators'));
    }

   public function show(Indicator $indicator, IndicatorTargetCalculationService $service, Request $request)
{
    $currentYear = $request->integer('year', now()->year);
    
    // 1. حساب المستهدفات التراكمية (السلسلة كاملة للرسم الخطي)
    $calculatedTargets = collect($service->calculateTargets($indicator, true));
    
    // 2. ترتيب القطاعات للسنة الحالية
    $sectorRanking = $service->getSectorsRanking($indicator, $currentYear);
    
    // 3. استخراج بيانات KPI للسنة الحالية فقط
    $currentYearKPI = $calculatedTargets->firstWhere('year', $currentYear);

    // 4. جلب المحقق الفعلي للقطاعات في السنة الحالية (لتفاصيل الفترات والملاحظات)
    $achievementsThisYear = $indicator->achieved()
        ->with('sector') // تأكد من وجود علاقة sector في موديل IndicatorAchievement
        ->where('year', $currentYear)
        ->get();

    // 5. حساب إنجاز الفترات (الربع الأول، الربع الثاني...)
    $periodBreakdown = [];
    $periods = match ($indicator->period) {
        'quarterly'   => [1 => 'الربع 1', 2 => 'الربع 2', 3 => 'الربع 3', 4 => 'الربع 4'],
        'half_yearly' => [1 => 'النصف 1', 2 => 'النصف 2'],
        'monthly'     => [1=>'يناير', 2=>'فبراير', 3=>'مارس', 4=>'أبريل', 5=>'مايو', 6=>'يونيو', 7=>'يوليو', 8=>'أغسطس', 9=>'سبتمبر', 10=>'أكتوبر', 11=>'نوفمبر', 12=>'ديسمبر'],
        default       => [1 => 'سنوي'],
    };

    foreach ($periods as $index => $label) {
        $periodData = $achievementsThisYear->where('period_index', $index);
        $val = $indicator->unit === 'percentage' 
            ? $periodData->avg('achieved_value') 
            : $periodData->sum('achieved_value');
        $periodBreakdown[$label] = $val ?? 0;
    }

    // 6. سجل الملاحظات للسنة الحالية
    $notesLog = $achievementsThisYear->whereNotNull('notes')->where('notes', '!=', '');

    return view('indicator.show', compact(
        'indicator', 'calculatedTargets', 'sectorRanking', 
        'currentYear', 'currentYearKPI', 'achievementsThisYear', 
        'periodBreakdown', 'notesLog'
    ));
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
