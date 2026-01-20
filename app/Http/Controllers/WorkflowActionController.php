<?php

namespace App\Http\Controllers;

use App\Models\Activity;
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
     * Note: Steps don't have workflows - only Activities do.
     */
    public function pendingSteps()
    {
        // Steps don't have workflow relationships
        // Return empty collection
        $activities = $this->workflowService->getPendingStepsForUser(Auth::user());

        return view('workflow.pending', compact('activities'));
    }

    /**
     * Submit a step to its workflow (moves from draft to first stage).
     */
    public function submit(Request $request, Activity $activity)
    {
        $request->validate([
            'comments' => 'nullable|string|max:1000',
        ]);

        try {
            $this->workflowService->submitStep(
                $activity,
                Auth::user(),
                $request->comments
            );

            return back()->with('success', 'تم إرسال النشاط بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Approve a step (moves to next stage or completes the workflow).
     */
    public function approve(Request $request, Activity $activity)
    {
        $request->validate([
            'comments' => 'nullable|string|max:1000',
        ]);

        try {
            $this->workflowService->approveStep(
                $activity,
                Auth::user(),
                $request->comments
            );

            return back()->with('success', 'تمت الموافقة على النشاط بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Return a step to a previous stage.
     */
    public function return(Request $request, Activity $activity)
    {
        $request->validate([
            'target_stage_id' => 'nullable|exists:workflow_stages,id',
            'comments' => 'nullable|string|max:1000',
            'step_feedbacks' => 'nullable|array',
            'step_feedbacks.*' => 'nullable|string|max:1000',
        ]);

        try {
            $this->workflowService->returnStep(
                $activity,
                Auth::user(),
                $request->target_stage_id,
                $request->comments,
                $request->input('step_feedbacks', [])
            );

            return back()->with('success', 'تمت إعادة النشاط بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject a step (terminates the workflow).
     */
    public function reject(Request $request, Activity $activity)
    {
        $request->validate([
            'comments' => 'required|string|max:1000',
        ]);

        try {
            $this->workflowService->rejectStep(
                $activity,
                Auth::user(),
                $request->comments
            );

            return back()->with('success', 'تم رفض النشاط');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Assign a workflow to a step.
     */
    /**
     * Start the default workflow for an activity.
     */
    public function start(Request $request, Activity $activity)
    {
        try {
            // 1. Identify Entity Type
            $entityType = $activity->getMorphClass();

            // 2. Find matching Workflow Definition
            $workflow = Workflow::where('entity_type', $entityType)
                ->where('is_active', true)
                ->first();

            if (!$workflow) {
                throw new \Exception('No active workflow definition found for this item type.');
            }

            // 3. Assign Workflow
            $this->workflowService->assignWorkflow(
                $activity,
                $workflow->id,
                Auth::user(),
                false // Do not auto-submit, start as draft
            );

            return back()->with('success', 'تم بدء سير العمل بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the transition history for a step.
     */
    public function history(Activity $activity)
    {
        $activity->load(['transitions.actor', 'transitions.fromStage', 'transitions.toStage', 'workflowInstance.workflow']);

        return view('workflow.history', compact('activity'));
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
