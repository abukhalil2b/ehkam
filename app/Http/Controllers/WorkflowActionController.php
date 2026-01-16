<?php

namespace App\Http\Controllers;

use App\Models\Step;
use App\Models\Workflow;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowActionController extends Controller
{
    protected WorkflowService $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Display steps pending action by the current user.
     */
    public function pendingSteps()
    {
        $user = Auth::user();
        $steps = $user->pendingWorkflowSteps()
            ->load(['workflow', 'currentStage.team', 'creator', 'project']);

        return view('workflow.pending', compact('steps'));
    }

    /**
     * Submit a step to its workflow (moves from draft to first stage).
     */
    public function submit(Request $request, Step $step)
    {
        $request->validate([
            'comments' => 'nullable|string|max:1000',
        ]);

        try {
            $this->workflowService->submitStep(
                $step,
                Auth::user(),
                $request->comments
            );

            return back()->with('success', 'تم إرسال الخطوة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Approve a step (moves to next stage or completes the workflow).
     */
    public function approve(Request $request, Step $step)
    {
        $request->validate([
            'comments' => 'nullable|string|max:1000',
        ]);

        try {
            $this->workflowService->approveStep(
                $step,
                Auth::user(),
                $request->comments
            );

            return back()->with('success', 'تمت الموافقة على الخطوة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Return a step to a previous stage.
     */
    public function return(Request $request, Step $step)
    {
        $request->validate([
            'target_stage_id' => 'nullable|exists:workflow_stages,id',
            'comments' => 'nullable|string|max:1000',
        ]);

        try {
            $this->workflowService->returnStep(
                $step,
                Auth::user(),
                $request->target_stage_id,
                $request->comments
            );

            return back()->with('success', 'تمت إعادة الخطوة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject a step (terminates the workflow).
     */
    public function reject(Request $request, Step $step)
    {
        $request->validate([
            'comments' => 'required|string|max:1000',
        ]);

        try {
            $this->workflowService->rejectStep(
                $step,
                Auth::user(),
                $request->comments
            );

            return back()->with('success', 'تم رفض الخطوة');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Assign a workflow to a step.
     */
    public function assignWorkflow(Request $request, Step $step)
    {
        $request->validate([
            'workflow_id' => 'required|exists:workflows,id',
            'auto_submit' => 'boolean',
        ]);

        try {
            $this->workflowService->assignWorkflow(
                $step,
                $request->workflow_id,
                Auth::user(),
                $request->boolean('auto_submit', false)
            );

            return back()->with('success', 'تم تعيين سير العمل بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the transition history for a step.
     */
    public function history(Step $step)
    {
        $step->load(['transitions.actor', 'transitions.fromStage', 'transitions.toStage', 'workflow']);

        return view('workflow.history', compact('step'));
    }

    /**
     * Get available workflows for selection.
     */
    public function availableWorkflows()
    {
        $workflows = Workflow::where('is_active', true)
            ->withCount('stages')
            ->get();

        return response()->json($workflows);
    }
}
