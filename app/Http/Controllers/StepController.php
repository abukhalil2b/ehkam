<?php

namespace App\Http\Controllers;

use App\Enums\StepStatus;
use App\Enums\StepPhase;
use App\Models\Activity;
use App\Models\Indicator;
use App\Models\OrgUnit;
use App\Models\PeriodTemplate;
use App\Models\Project;
use App\Models\Step;
use App\Models\StepOrgUnitTask;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Added for cleaner validation

class StepController extends Controller
{
    // Show steps for the project
    public function index(Project $project, $activity = null)
    {
        // 1. Use Enum for phases
        $phases = StepPhase::all();

        $stepsQuery = Step::where('project_id', $project->id);

        if (!is_null($activity)) {
            $stepsQuery->where('activity_id', $activity);
        }

        $steps = $stepsQuery->orderBy('ordered', 'asc')->get();

        $activities = Activity::where('project_id', $project->id)->get();

        $indicator = Indicator::find($project->indicator_id);

        $periodTemplates = $indicator
            ? PeriodTemplate::where('cate', $indicator->period)->get()
            : collect();

        // Completion stats for the shared project-header partial
        $allSteps           = Step::where('project_id', $project->id)->get();
        $totalSteps         = $allSteps->count();
        $stepsDoneCount     = $allSteps->whereIn('status', ['completed', 'approved'])->count();
        $completionPercentage = $totalSteps > 0 ? round(($stepsDoneCount / $totalSteps) * 100) : 0;

        return view('step.index', compact(
            'project', 'steps', 'phases', 'indicator', 'activities',
            'completionPercentage', 'stepsDoneCount', 'totalSteps'
        ));
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
            'phase' => ['nullable', Rule::in(array_keys(StepPhase::all()))],
            'supporting_document' => 'nullable|string',
            'ordered' => 'nullable|integer',
            'activity_id' => 'required|integer',
            'is_need_evidence_file' => 'boolean',
            'is_need_to_put_target' => 'boolean',
          
        ]);

       
            Step::create([
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

       

        return redirect()->route('step.index', $project->id)
            ->with('success', 'تمت إضافة الخطوة بنجاح');
    }




    public function show(Step $step)
    {
        // 4. Use Enum
        $phases = StepPhase::all();

        $step->load([
            'stepOrgUnitTasks.OrgUnit',
            'stepOrgUnitTasks.periodTemplate',
            'activity',
            'project',
            'evidenceFiles.uploader',
            'evidenceFiles.reviewer',
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
        $phases = StepPhase::all();
        return view('step.edit', compact('step', 'phases'));
    }

    public function update(Request $request, Step $step)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_percentage' => 'nullable|numeric|min:0|max:100',
            'phase' => ['nullable', Rule::in(array_keys(StepPhase::all()))],
            'is_need_evidence_file' => 'boolean',
            'is_need_to_put_target' => 'boolean',
            'supporting_document' => 'nullable|string',
        ]);

        $step->update($validated);

        return redirect()->route('step.show', $step->id)
            ->with('success', 'تم تحديث بيانات الخطوة بنجاح.');
    }
}
