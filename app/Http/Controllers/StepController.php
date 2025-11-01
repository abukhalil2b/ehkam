<?php

namespace App\Http\Controllers;

use App\Models\OrganizationalUnit;
use App\Models\Project;
use App\Models\Step;
use App\Models\StepEvidenceFile;
use Illuminate\Http\Request;

class StepController extends Controller
{
    // Show steps for the project
    public function index(Project $project)
    {

        $phases = [
            'preparation' => ['title' => 'التحضير', 'weight' => '15%'],
            'planning' => ['title' => 'التخطيط والتطوير', 'weight' => '20%'],
            'implementation' => ['title' => 'التنفيذ', 'weight' => '30%'],
            'review' => ['title' => 'المراجعة', 'weight' => '20%'],
            'approval' => ['title' => 'الاعتماد والإغلاق', 'weight' => '15%'],
        ];

        // load steps for the project, ordered by `ordered` (or id if you prefer)
        $steps = Step::where('project_id', $project->id)
            ->orderBy('ordered', 'asc')
            ->get();

        $organizational_units = OrganizationalUnit::all();

        return view('step.index', compact('project', 'steps', 'phases','organizational_units'));
    }

    // Store new step
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_percentage' => 'nullable|numeric|min:0|max:100',
            'phase' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'supporting_documents' => 'nullable|string',
            'assigned_divisions' => 'nullable|array',
            'ordered' => 'nullable|integer',
        ]);

        Step::create([
            'project_id' => $project->id,
            'name' => $validated['name'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'target_percentage' => $validated['target_percentage'] ?? 0,
            'phase' => $validated['phase'] ?? null,
            'status' => $validated['status'] ?? 'not_started',
            'supporting_documents' => $validated['supporting_documents'] ?? null,
            // store assigned divisions as JSON string in DB column (or as you prefer)
            'assigned_divisions' => isset($validated['assigned_divisions'])
                ? json_encode($validated['assigned_divisions'])
                : null,
            'ordered' => $validated['ordered'] ?? 0,
        ]);

        return redirect()->route('step.index', $project->id)
            ->with('success', 'تمت إضافة الخطوة بنجاح');
    }

    public function show(Step $step)
    {


        $phases = [
            'preparation' => ['title' => 'التحضير', 'weight' => '15%'],
            'planning' => ['title' => 'التخطيط والتطوير', 'weight' => '20%'],
            'implementation' => ['title' => 'التنفيذ', 'weight' => '30%'],
            'review' => ['title' => 'المراجعة', 'weight' => '20%'],
            'approval' => ['title' => 'الاعتماد والإغلاق', 'weight' => '15%'],
        ];

        $step->load('stepEvidenceFiles');
        return view('step.show', compact('step', 'phases'));
    }

    public function uploadEvidence(Request $request, Step $step)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:5120',
        ]);

        $path = $request->file('file')->store('evidence_files', 'public');

        StepEvidenceFile::create([
            'step_id' => $step->id,
            'file_path' => $path,
            'file_name' => $request->file('file')->getClientOriginalName(),
            'file_type' => $request->file('file')->getClientOriginalExtension(),
        ]);

        return back()->with('success', 'تم رفع الملف بنجاح.');
    }

    public function destroy(Step $step)
    {
        $projectId = $step->project_id;
        try {
            $step->delete();

            return redirect()
                ->route('step.index', $projectId)
                ->with('success', 'تم حذف الخطوة بنجاح.');
        } catch (\Exception $e) {
            return redirect()
                ->route('step.index', $projectId)
                ->with('error', 'حدث خطأ أثناء الحذف.');
        }
    }

    public function edit(Step $step)
    {
        $phases = [
            'preparation' => ['title' => 'التحضير', 'weight' => '15%'],
            'planning' => ['title' => 'التخطيط والتطوير', 'weight' => '20%'],
            'implementation' => ['title' => 'التنفيذ', 'weight' => '30%'],
            'review' => ['title' => 'المراجعة', 'weight' => '20%'],
        ];
        return view('step.edit', compact('step','phases'));
    }

    public function update(Request $request, Step $step)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_percentage' => 'nullable|numeric|min:0|max:100',
            'phase' => 'nullable|string|max:255',
            'status' => 'required|in:in_progress,completed,delayed',
            'assigned_divisions' => 'nullable|array',
            'assigned_divisions.*' => 'string|max:255',
            'is_need_evidence_file' => 'boolean',
            'supporting_documents' => 'nullable|string',
        ]);

        // Convert array to JSON
        if (isset($validated['assigned_divisions'])) {
            $validated['assigned_divisions'] = json_encode($validated['assigned_divisions']);
        }

        $step->update($validated);

        return redirect()->route('step.show', $step->id)
            ->with('success', 'تم تحديث بيانات الخطوة بنجاح.');
    }
}
