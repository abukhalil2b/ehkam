<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\OrganizationalUnit;
use App\Models\Project;
use App\Models\Sector;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Indirect;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Indicator $indicator)
    {
        $current_year = now()->year;

        $projects = Project::where('indicator_id', $indicator->id)
            ->where('current_year', $current_year)
            ->get();

        return view('project.index', compact('projects', 'indicator', 'current_year'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Indicator $indicator)
    {
        $sectors = Sector::all();

        return view('project.create', compact('sectors', 'indicator'));
    }



    // دالة لجلب الوحدات التابعة بناءً على الأب
    public function getUnitChildren($parentId)
    {
        $children = OrganizationalUnit::where('parent_id', $parentId)->get(['id', 'name']);
        return response()->json($children);
    }

    // دالة حفظ المشروع
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'indicator_id' => 'required|exists:indicators,id',
            'organizational_unit_id' => 'required', // القيمة النهائية المختارة
        ]);

        // 1. إنشاء المشروع
        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'indicator_id' => $request->indicator_id,
            'current_year' => date('Y'),
        ]);

        // 2. ربط المالك بالمشروع (Project Owner)
        $project->owners()->create([
            'organizational_unit_id' => $request->organizational_unit_id
        ]);

        return redirect()->back()->with('success', 'تم حفظ المشروع بنجاح');
    }

    public function edit(Project $project)
{
    // تحميل المالك والمؤشرات
    $project->load('owners.unit');
    $indicators = Indicator::all();
    
    // جلب المديريات (التي ليس لها أب) لتبدأ بها القوائم
    $sectors = OrganizationalUnit::whereNull('parent_id')->get();

    // تحديد المالك الحالي ومعرفة تسلسله الهرمي
    $currentOwner = $project->owners->first();
    $currentUnit = $currentOwner ? $currentOwner->unit : null;

    return view('project.edit', compact('project', 'indicators', 'sectors', 'currentUnit'));
}

public function update(Request $request, Project $project)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255|unique:projects,title,' . $project->id,
        'description' => 'nullable|string',
        'indicator_id' => 'required|exists:indicators,id',
        'organizational_unit_id' => 'required', // الوحدة الجديدة المختارة
    ]);

    // 1. تحديث بيانات المشروع
    $project->update([
        'title' => $validatedData['title'],
        'description' => $validatedData['description'],
        'indicator_id' => $validatedData['indicator_id'],
    ]);

    // 2. تحديث المالك (نحذف القديم ونضيف الجديد أو نعدله)
    $project->owners()->updateOrCreate(
        ['project_id' => $project->id],
        ['organizational_unit_id' => $request->organizational_unit_id]
    );

    return redirect()->route('project.show', $project->id)
        ->with('success', 'تم تحديث المشروع بنجاح!');
}

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['indicator', 'owners', 'activities']);

        // جلب الخطوات وحساب نسبة الإنجاز
        $steps = $project->steps; // تأكد من إضافة steps في الـ load أعلاه إذا أردت تحسين الأداء
        $totalSteps = $steps->count();
        $stepsDoneCount = $steps->where('status', 'completed')->count();

        // حساب النسبة المئوية
        $completionPercentage = $totalSteps > 0 ? round(($stepsDoneCount / $totalSteps) * 100) : 0;

        $current_year = date('Y');

        return view('project.show', compact('project', 'current_year', 'completionPercentage', 'steps', 'stepsDoneCount', 'totalSteps'));
    }


    public function taskShow(Project $project)
    {
        return view('project.task.show', compact('project'));
    }
}
