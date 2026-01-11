<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityWorkflow;
use App\Models\StepWorkflow;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function index(Request $request)
    {
        // Fetch Activity Workflows
        $activityWorkflows = ActivityWorkflow::with(['activity', 'assignedUser'])
            ->latest()
            ->get()
            ->map(function ($workflow) {
                return [
                    'type' => 'Activity',
                    'name' => $workflow->activity->title ?? 'N/A',
                    'stage' => $workflow->stage,
                    'status' => $workflow->status,
                    'last_updated' => $workflow->updated_at,
                    'assigned_to' => $workflow->assignedUser->name ?? '—',
                    'link' => route('activity.show', $workflow->activity_id), // Assuming this route exists
                ];
            });

        // Fetch Step Workflows
        $stepWorkflows = StepWorkflow::with(['step', 'assignedUser'])
            ->latest()
            ->get()
            ->map(function ($workflow) {
                return [
                    'type' => 'Step',
                    'name' => $workflow->step->name ?? 'N/A',
                    'stage' => $workflow->stage,
                    'status' => $workflow->status,
                    'last_updated' => $workflow->updated_at,
                    'assigned_to' => $workflow->assignedUser->name ?? '—',
                    'link' => route('step.show', $workflow->step_id),
                ];
            });

        // Merge and Sort
        $allWorkflows = $activityWorkflows->concat($stepWorkflows)->sortByDesc('last_updated');

        return view('admin.workflow.index', compact('allWorkflows'));
    }
}
