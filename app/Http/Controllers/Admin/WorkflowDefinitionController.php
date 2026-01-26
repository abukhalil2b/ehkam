<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workflow;
use App\Models\WorkflowStage;
use App\Models\WorkflowTeam;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkflowDefinitionController extends Controller
{
    /**
     * Display a listing of workflows.
     */
    public function index()
    {
        $workflows = Workflow::withCount(['stages', 'instances'])->get();

        return view('admin.workflow.definitions.index', compact('workflows'));
    }

    /**
     * Show the form for creating a new workflow.
     */
    public function create()
    {
        $teams = WorkflowTeam::orderBy('name')->get();

        return view('admin.workflow.definitions.create', compact('teams'));
    }

    /**
     * Store a newly created workflow.
     */
    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'entity_type' => [
                'required',
                'string',
                Rule::in([
                    'App\Models\Activity',
                    'App\Models\Step',
                    'App\Models\Project',
                    'App\Models\AppointmentRequest',
                ]),
                'unique:workflows,entity_type'
            ],
            'is_active' => 'nullable|boolean',
        ]);

        $workflow = Workflow::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
            'entity_type' => $request->entity_type,
        ]);

        return redirect()
            ->route('admin.workflow.definitions.edit', $workflow)
            ->with('success', 'تم إنشاء سير العمل بنجاح. يمكنك الآن إضافة المراحل.');
    }

    /**
     * Display the specified workflow.
     */
    public function show(Workflow $definition)
    {
        $definition->load(['stages.team']);

        return view('admin.workflow.definitions.show', compact('definition'));
    }

    /**
     * Show the form for editing the specified workflow.
     */
    public function edit(Workflow $definition)
    {
        $definition->load(['stages.team']);
        $teams = WorkflowTeam::orderBy('name')->get();

        return view('admin.workflow.definitions.edit', compact('definition', 'teams'));
    }

    /**
     * Update the specified workflow.
     */
    public function update(Request $request, Workflow $definition)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $definition->update([
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => $request->has('is_active')
                ? $request->boolean('is_active')
                : false,
        ]);

        return redirect()
            ->route('admin.workflow.definitions.index')
            ->with('success', 'تم تحديث سير العمل بنجاح');
    }


    /**
     * Remove the specified workflow.
     */
    public function destroy(Workflow $definition)
    {
        if (!$definition->canBeDeleted()) {
            return back()->with('error', 'لا يمكن حذف سير العمل لأن هناك خطوات نشطة تستخدمه');
        }

        $definition->delete();

        return redirect()
            ->route('admin.workflow.definitions.index')
            ->with('success', 'تم حذف سير العمل بنجاح');
    }

    /**
     * Check if workflow modification is allowed
     */
    private function checkModificationAllowed(Workflow $workflow)
    {
        if ($workflow->isUsed()) {
            abort(403, 'لا يمكن تعديل هيكلية سير العمل لأنه مستخدم بالفعل في أنشطة.');
        }
    }

    // ========== STAGE MANAGEMENT ==========

    /**
     * Store a new stage for a workflow.
     */
    public function storeStage(Request $request, Workflow $workflow)
    {
        $this->checkModificationAllowed($workflow);

        $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'required|exists:workflow_teams,id',
            'allowed_days' => 'required|integer|min:0|max:90',
            'can_approve' => 'boolean',
            'can_return' => 'boolean',
            'assignment_type' => 'in:team,user,role',
        ]);

        // Get the next order number (using gapped integers: 10, 20, 30...)
        $maxOrder = $workflow->stages()->max('order') ?? 0;
        $newOrder = $maxOrder + 10;

        $stage = WorkflowStage::create([
            'workflow_id' => $workflow->id,
            'team_id' => $request->team_id,
            'order' => $newOrder,
            'name' => $request->name,
            'allowed_days' => $request->allowed_days,
            'can_approve' => $request->has('can_approve'),
            'can_return' => $request->has('can_return'),
            'assignment_type' => $request->input('assignment_type', 'team'),
        ]);

        return back()->with('success', 'تم إضافة المرحلة بنجاح');
    }

    /**
     * Update a stage.
     */
    public function updateStage(Request $request, WorkflowStage $stage)
    {
        $this->checkModificationAllowed($stage->workflow);

        $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'required|exists:workflow_teams,id',
            'allowed_days' => 'required|integer|min:1',
            'can_approve' => 'boolean',
            'can_return' => 'boolean',
            'assignment_type' => 'in:team,user,role',
        ]);

        $stage->update([
            'name' => $request->name,
            'team_id' => $request->team_id,
            'allowed_days' => $request->allowed_days,
            'can_approve' => $request->has('can_approve'),
            'can_return' => $request->has('can_return'),
            'assignment_type' => $request->input('assignment_type', 'team'),
        ]);

        return back()->with('success', 'تم تحديث المرحلة بنجاح');
    }

    /**
     * Delete a stage.
     */
    public function destroyStage(WorkflowStage $stage)
    {
        $this->checkModificationAllowed($stage->workflow);

        if (!$stage->canBeDeleted()) {
            return back()->with('error', 'لا يمكن حذف المرحلة لأن هناك خطوات حالياً في هذه المرحلة');
        }

        $workflowId = $stage->workflow_id;
        $stage->delete();

        return back()->with('success', 'تم حذف المرحلة بنجاح');
    }

    /**
     * Reorder stages (AJAX).
     */
    public function reorderStages(Request $request, Workflow $workflow)
    {
        $this->checkModificationAllowed($workflow);

        $request->validate([
            'stage_ids' => 'required|array',
            'stage_ids.*' => 'exists:workflow_stages,id',
        ]);

        // Update order based on array position
        foreach ($request->stage_ids as $index => $stageId) {
            WorkflowStage::where('id', $stageId)
                ->where('workflow_id', $workflow->id)
                ->update(['order' => ($index + 1) * 10]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Reindex all stages to clean up gaps.
     */
    public function reindexStages(Workflow $workflow)
    {
        $this->checkModificationAllowed($workflow);

        $workflow->reindexStages();

        return back()->with('success', 'تم إعادة ترتيب المراحل بنجاح');
    }

    /**
     * Insert a stage between two existing stages.
     */
    public function insertStage(Request $request, Workflow $workflow)
    {
        $this->checkModificationAllowed($workflow);

        $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'required|exists:workflow_teams,id',
            'allowed_days' => 'required|integer|min:1',
            'after_stage_id' => 'nullable|exists:workflow_stages,id',
            'before_stage_id' => 'nullable|exists:workflow_stages,id',
        ]);

        $afterStage = $request->after_stage_id
            ? WorkflowStage::find($request->after_stage_id)
            : null;
        $beforeStage = $request->before_stage_id
            ? WorkflowStage::find($request->before_stage_id)
            : null;

        // Calculate the new order
        if ($afterStage && $beforeStage) {
            // Insert between two stages
            $newOrder = (int) (($afterStage->order + $beforeStage->order) / 2);

            // If no gap available, reindex first
            if ($newOrder === $afterStage->order || $newOrder === $beforeStage->order) {
                $workflow->reindexStages();
                $afterStage->refresh();
                $beforeStage->refresh();
                $newOrder = (int) (($afterStage->order + $beforeStage->order) / 2);
            }
        } elseif ($afterStage) {
            // Insert after a specific stage
            $nextStage = $afterStage->nextStage();
            $newOrder = $nextStage
                ? (int) (($afterStage->order + $nextStage->order) / 2)
                : $afterStage->order + 10;
        } elseif ($beforeStage) {
            // Insert before a specific stage
            $prevStage = $beforeStage->previousStage();
            $newOrder = $prevStage
                ? (int) (($prevStage->order + $beforeStage->order) / 2)
                : $beforeStage->order - 10;
        } else {
            // Insert at the end
            $maxOrder = $workflow->stages()->max('order') ?? 0;
            $newOrder = $maxOrder + 10;
        }

        WorkflowStage::create([
            'workflow_id' => $workflow->id,
            'team_id' => $request->team_id,
            'order' => max(1, $newOrder), // Ensure order is positive
            'name' => $request->name,
            'allowed_days' => $request->allowed_days,
            'can_approve' => $request->has('can_approve'),
            'can_return' => $request->has('can_return'),
            'assignment_type' => 'team',
        ]);

        return back()->with('success', 'تم إضافة المرحلة بنجاح');
    }
}
