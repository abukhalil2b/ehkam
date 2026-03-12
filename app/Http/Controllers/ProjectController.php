<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\OrgUnit;
use App\Models\Project;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Indirect;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Indicator $indicator)
    {
        $currentYear = now()->year;
        $projects = Project::where('indicator_id', $indicator->id)
            ->get();

        return view('project.index', compact('projects', 'indicator', 'currentYear'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Indicator $indicator)
    {
        $executors = OrgUnit::where('id', 41)->get();
        return view('project.create', compact('executors', 'indicator'));
    }



    // دالة لجلب الوحدات التابعة بناءً على الأب
    public function getUnitChildren($parentId)
    {
        $children = OrgUnit::where('parent_id', $parentId)->get(['id', 'name']);
        return response()->json($children);
    }

    // دالة حفظ المشروع
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'indicator_id' => 'required|exists:indicators,id',
            'org_unit_id' => 'required', // القيمة النهائية المختارة
        ]);

        // 1. إنشاء المشروع
        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'indicator_id' => $request->indicator_id,
            'executor_id' => 1,
            'current_year' => date('Y'),
        ]);


        return redirect()->back()->with('success', 'تم حفظ المشروع بنجاح');
    }

    public function edit(Project $project)
    {
        $indicators = Indicator::all();
        
        $executors = OrgUnit::where('id', 41)->get();
        return view('project.edit', compact('project', 'indicators', 'executors'));
    }

    public function update(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'indicator_id' => 'required|exists:indicators,id',
            'executor_id' => 'required',
        ]);

        // 1. تحديث بيانات المشروع
        $project->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'indicator_id' => $validatedData['indicator_id'],
            'executor_id' =>  $validatedData['executor_id'],
        ]);


        return redirect()->route('project.show', $project->id)
            ->with('success', 'تم تحديث المشروع بنجاح!');
    }
    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['indicator', 'activities.steps.feedbacks', 'executor']);

        // جلب الخطوات وحساب نسبة الإنجاز
        $steps = $project->steps; // تأكد من إضافة steps في الـ load أعلاه إذا أردت تحسين الأداء
        $totalSteps = $steps->count();
        $stepsDoneCount = $steps->where('status', 'completed')->count();

        // حساب النسبة المئوية
        $completionPercentage = $totalSteps > 0 ? round(($stepsDoneCount / $totalSteps) * 100) : 0;


        return view('project.show', compact('project', 'completionPercentage', 'steps', 'stepsDoneCount', 'totalSteps'));
    }


    public function taskShow(Project $project)
    {
        $project->load('executor');
        return view('project.task.show', compact('project'));
    }

    // ==========================================
    // WORKFLOW TRANSITIONS
    // ==========================================

    public function submit(Request $request, Project $project)
    {
        $this->authorize('submit', $project);

        $project->update([
            'status' => 'submitted'
        ]);

        return redirect()->back()->with('success', 'تم تقديم المشروع للمراجعة بنجاح.');
    }

    public function approve(Request $request, Project $project)
    {
        $this->authorize('approve', $project);

        $project->update([
            'status' => 'approved'
        ]);

        return redirect()->back()->with('success', 'تم اعتماد المشروع بنجاح.');
    }

    public function return(Request $request, Project $project)
    {
        $this->authorize('approve', $project); // Same permission group

        $project->update([
            'status' => 'returned'
        ]);

        if ($request->has('step_feedbacks') && is_array($request->step_feedbacks)) {
            foreach ($request->step_feedbacks as $stepId => $notes) {
                if (!empty($notes)) {
                    \App\Models\StepFeedback::create([
                        'step_id' => $stepId,
                        'notes' => $notes,
                        'created_by' => auth()->id(),
                    ]);
                }
            }
        }

        return redirect()->back()->with('info', 'تمت إعادة المشروع للتعديل.');
    }

    public function reject(Request $request, Project $project)
    {
        $this->authorize('approve', $project); // Same permission group

        $project->update([
            'status' => 'rejected'
        ]);

        if ($request->has('step_feedbacks') && is_array($request->step_feedbacks)) {
            foreach ($request->step_feedbacks as $stepId => $notes) {
                if (!empty($notes)) {
                    \App\Models\StepFeedback::create([
                        'step_id' => $stepId,
                        'notes' => $notes,
                        'created_by' => auth()->id(),
                    ]);
                }
            }
        }

        return redirect()->back()->with('error', 'تم رفض المشروع وإغلاقه.');
    }

}
