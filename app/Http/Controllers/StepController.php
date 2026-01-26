<?php

namespace App\Http\Controllers;

use App\Enums\StepStatus;
use App\Enums\WorkflowStage;
use App\Models\Activity;
use App\Models\Indicator;
use App\Models\OrgUnit;
use App\Models\PeriodTemplate;
use App\Models\Project;
use App\Models\Step;
use App\Models\WorkflowRequirement;
use App\Models\StepOrgUnitTask;
use App\Models\Role;
use App\Models\Workflow;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Added for cleaner validation

class StepController extends Controller
{
    // Show steps for the project
    public function index(Project $project, $activity = null)
    {
        // 1. Use Enum for phases
        $phases = WorkflowStage::all();

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
            // 2. Validate phase against Enum keys
            'phase' => ['nullable', Rule::in(array_keys(WorkflowStage::all()))],
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
            'status' => 'draft',
            'is_need_evidence_file' => $validated['is_need_evidence_file'] ?? 0,
            'is_need_to_put_target' => $validated['is_need_to_put_target'] ?? 0,
            'supporting_document' => $validated['supporting_document'] ?? null,
            'ordered' => $validated['ordered'] ?? 0,
            'activity_id' => $validated['activity_id'],
        ]);

        if ($request->boolean('is_need_to_put_target') && !empty($validated['org_unit_ids'])) {
            $indicator = Indicator::findOrFail($project->indicator_id);
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

        return redirect()->route('step.index', $project->id)
            ->with('success', 'تمت إضافة الخطوة بنجاح');
    }


    /**
     * Submit a Step to its workflow.
     * 
     * This method:
     * 1. Finds the workflow definition for Step models
     * 2. Assigns the workflow to the step (creates WorkflowInstance)
     * 3. Auto-submits to first stage (moves from draft to in_progress)
     * 
     * Prerequisites:
     * - A Workflow must exist with entity_type = 'App\Models\Step'
     * - The workflow must have at least one stage defined
     * - The workflow must be active (is_active = true)
     * 
     * @param Request $request Must contain 'step_id'
     * @param WorkflowService $service Injected workflow service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stepSubmit(Request $request, WorkflowService $service)
    {
        // Validate request
        $request->validate([
            'step_id' => 'required|exists:steps,id',
            'comments' => 'nullable|string|max:1000',
        ]);

        $step = Step::findOrFail($request->step_id);
        $user = Auth::user();

        $instance = $step->workflowInstance;

        // Case 1: Step was returned - allow resubmission
        if ($instance && $instance->status === 'returned') {
            try {
                $service->submitStep($step, $user, $request->comments ?? 'إعادة إرسال بعد التعديل');
                return back()->with('success', 'تم إعادة إرسال الخطوة بنجاح.');
            } catch (\Exception $e) {
                return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
            }
        }

        // Case 2: Step is already in progress or completed
        if ($instance && !$instance->isDraft()) {
            return back()->with('error', 'هذه الخطوة موجودة بالفعل في سير العمل.');
        }

        // Case 3: New submission - find workflow and assign
        // Search for workflow by entity_type - must be unique per model type
        // Step::class resolves to 'App\Models\Step' (stored in workflows.entity_type)
        // The entity_type column is unique, so only one workflow per model type exists
        try {
            $workflow = Workflow::where('entity_type', Step::class)
                ->where('is_active', true)
                ->firstOrFail(); // Throws ModelNotFoundException if not found
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'لا يوجد سير عمل مُعرّف لهذا النوع. يرجى إنشاء سير عمل للخطوات أولاً.');
        }

        try {
            // Assign workflow and auto-submit to first stage
            // When autoSubmit=true, it automatically calls submitStep internally
            $service->assignWorkflow($step, $workflow->id, $user, autoSubmit: true);

            return back()->with('success', 'تم إرسال الخطوة إلى سير العمل بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Approve a Step in workflow (moves to next stage or completes).
     * 
     * المرحلة الأولى: فريق التخطيط يراجع ويوافق
     * المرحلة الثانية: مشرف التنفيذ لتوزيع المستهدفات
     */
    public function stepApprove(Request $request, Step $step, WorkflowService $service)
    {
        $request->validate([
            'comments' => 'nullable|string|max:1000',
        ]);

        if (!$step->canBeActedUpon()) {
            return back()->with('error', 'لا يمكن اتخاذ إجراء على هذه الخطوة.');
        }

        try {
            $service->approveStep($step, Auth::user(), $request->comments);
            return back()->with('success', 'تمت الموافقة على الخطوة بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Return a Step to previous stage with feedback.
     * 
     * يستخدم عندما يجد فريق التخطيط خطأ في الإجراء
     * يتم إرجاع الخطوة مع كتابة التعليق
     */
    public function stepReturn(Request $request, Step $step, WorkflowService $service)
    {
        $request->validate([
            'comments' => 'required|string|max:1000',
            'target_stage_id' => 'nullable|exists:workflow_stages,id',
        ]);

        if (!$step->canBeActedUpon()) {
            return back()->with('error', 'لا يمكن اتخاذ إجراء على هذه الخطوة.');
        }

        try {
            $service->returnStep(
                $step,
                Auth::user(),
                $request->target_stage_id,
                $request->comments
            );
            return back()->with('success', 'تم إرجاع الخطوة بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Reject a Step (terminates the workflow).
     */
    public function stepReject(Request $request, Step $step, WorkflowService $service)
    {
        $request->validate([
            'comments' => 'required|string|max:1000',
        ]);

        if (!$step->canBeActedUpon()) {
            return back()->with('error', 'لا يمكن اتخاذ إجراء على هذه الخطوة.');
        }

        try {
            $service->rejectStep($step, Auth::user(), $request->comments);
            return back()->with('success', 'تم رفض الخطوة.');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    public function show(Step $step)
    {
        // 4. Use Enum
        $phases = WorkflowStage::all();

        $step->load([
            'stepOrgUnitTasks.OrgUnit',
            'stepOrgUnitTasks.periodTemplate',
            'activity',
            'project',
            // Workflow relationships
            'workflowInstance.workflow.stages.team',
            'workflowInstance.currentStage.team',
            'transitions.actor',
            'transitions.toStage',
            'transitions.fromStage',
        ]);

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

        $overallTarget = array_sum(array_column($unitData, 'total_target'));

        return view('step.show', compact('step', 'phases', 'unitData', 'overallTarget'));
    }

    public function uploadEvidence(Request $request, Step $step)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xlsx,xls,png,jpg,jpeg|max:5120',
        ]);

        $path = $request->file('file')->store('evidence_files', 'public');

        WorkflowRequirement::create([
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
        // 5. Use Enum
        $phases = WorkflowStage::all();
        return view('step.edit', compact('step', 'phases'));
    }

    public function update(Request $request, Step $step)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_percentage' => 'nullable|numeric|min:0|max:100',
            'phase' => ['nullable', Rule::in(array_keys(WorkflowStage::all()))],
            'is_need_evidence_file' => 'boolean',
            'is_need_to_put_target' => 'boolean',
            'supporting_document' => 'nullable|string',
        ]);

        $step->update($validated);

        return redirect()->route('step.show', $step->id)
            ->with('success', 'تم تحديث بيانات الخطوة بنجاح.');
    }
}
