<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Indicator;
use App\Models\OrgUnit;
use App\Models\PeriodTemplate;
use App\Models\Project;
use App\Models\Step;
use App\Models\StepEvidenceFile;
use App\Models\StepOrgUnitTask;
use App\Models\StepWorkflow;
use App\Models\Profile;
use Illuminate\Http\Request;

class StepController extends Controller
{
    // Show steps for the project
    public function index(Project $project, $activity = null)
    {

        $phases = [
            'preparation' => ['title' => 'التحضير', 'weight' => '15%'],
            'planning' => ['title' => 'التخطيط والتطوير', 'weight' => '20%'],
            'implementation' => ['title' => 'التنفيذ', 'weight' => '30%'],
            'review' => ['title' => 'المراجعة', 'weight' => '20%'],
            'approval' => ['title' => 'الاعتماد والإغلاق', 'weight' => '15%'],
        ];

        $stepsQuery = Step::where('project_id', $project->id);

        if (!is_null($activity)) {
            $stepsQuery->where('activity_id', $activity);
        }

        $steps = $stepsQuery->orderBy('ordered', 'asc')->get();

        $activities = Activity::where('project_id', $project->id)->get();

        $org_units = OrgUnit::where('type', 'Directorate')->get();

        $indicator = Indicator::find($project->indicator_id);

        $periodTemplates = $indicator
            ? PeriodTemplate::where('cate', $indicator->period)->get()
            : collect();


        return view('step.index', compact('project', 'steps', 'phases', 'org_units', 'periodTemplates', 'indicator', 'activities'));
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
            'supporting_document' => 'nullable|string',
            'ordered' => 'nullable|integer',
            'activity_id' => 'required|integer',
            'is_need_evidence_file' => 'boolean',
            'is_need_to_put_target' => 'boolean',
            'org_unit_ids' => [
                'nullable',
                'array',
                'required_if:is_need_to_put_target,1',
            ],
            'org_unit_ids.*' => 'exists:org_units,id',
        ]);

        $indicator = Indicator::findOrFail($project->indicator_id);

        $periodTemplates = PeriodTemplate::where('cate', $indicator->period)->get();

        if (!$request->boolean('is_need_to_put_target')) {
            $validated['org_unit_ids'] = [];
        }

        $step = Step::create([
            'project_id' => $project->id,
            'name' => $validated['name'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'target_percentage' => $validated['target_percentage'] ?? 0,
            'phase' => $validated['phase'] ?? null,
            'status' => $validated['status'] ?? 'not_started',
            'is_need_evidence_file' => $validated['is_need_evidence_file'] ?? 0,
            'is_need_to_put_target' => $validated['is_need_to_put_target'] ?? 0,
            'supporting_document' => $validated['supporting_document'] ?? null,
            'ordered' => $validated['ordered'] ?? 0,
            'activity_id' => $validated['activity_id'],
        ]);

        if ($request->boolean('is_need_to_put_target') && !empty($validated['org_unit_ids'])) {

            $periodTemplates = PeriodTemplate::where('cate', $indicator->period)->get();

            $periodTargets = $request->input('period_targets', []);

            foreach ($validated['org_unit_ids'] as $orgUnitId) {
                foreach ($periodTemplates as $template) {
                    StepOrgUnitTask::create([
                        'step_id' => $step->id,
                        'org_unit_id' => $orgUnitId,
                        'period_template_id' => $template->id,
                        'target' => $periodTargets[$template->id] ?? 0,
                        'achieved' => 0,
                    ]);
                }
            }
        }

        // Initialize Workflow
        $qaProfile = Profile::where('title', 'Quality Assurance')->first();
        StepWorkflow::create([
            'step_id' => $step->id,
            'stage' => 'verification',
            'status' => 'pending',
            'assigned_role' => $qaProfile ? $qaProfile->id : null,
        ]);

        // Send Notification to Users with 'Quality Assurance' Role
        if ($qaProfile) {
            $usersToNotify = \App\Models\User::whereHas('profiles', function ($query) use ($qaProfile) {
                // Assuming 'user_profile' or similar pivot table logic is handled by 'profiles' relation
                $query->where('profiles.id', $qaProfile->id);
            })->get();

            if ($usersToNotify->isNotEmpty()) {
                \Illuminate\Support\Facades\Notification::send($usersToNotify, new \App\Notifications\StepWorkflowAssigned($step));
            }
        }


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

        // Load relations
        $step->load([
            'stepEvidenceFiles',
            'stepOrgUnitTasks.OrgUnit',
            'stepOrgUnitTasks.periodTemplate'
        ]);

        // Group tasks by organizational unit
        $unitData = [];
        foreach ($step->stepOrgUnitTasks as $task) {
            $unitId = $task->org_unit_id;
            $unitName = optional($task->OrgUnit)->name ?? '—';

            if (!isset($unitData[$unitId])) {
                $unitData[$unitId] = [
                    'name' => $unitName,
                    'periods' => [],
                    'total_target' => 0,
                    'total_achieved' => 0,
                ];
            }

            $unitData[$unitId]['periods'][$task->periodTemplate->name ?? '—'] = [
                'target' => $task->target,
                'achieved' => $task->achieved,
                'percentage' => $task->target > 0 ? round(($task->achieved / $task->target) * 100, 2) : 0,
            ];

            $unitData[$unitId]['total_target'] += $task->target;
            $unitData[$unitId]['total_achieved'] += $task->achieved;
        }

        // Calculate overall totals
        $overallTarget = array_sum(array_column($unitData, 'total_target'));

        return view('step.show', compact('step', 'phases', 'unitData', 'overallTarget'));
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
        return view('step.edit', compact('step', 'phases'));
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
            'is_need_evidence_file' => 'boolean',
            'is_need_to_put_target' => 'boolean',
            'supporting_document' => 'nullable|string',
        ]);

        $step->update($validated);

        return redirect()->route('step.show', $step->id)
            ->with('success', 'تم تحديث بيانات الخطوة بنجاح.');
    }
}
