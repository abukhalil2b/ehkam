<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Activity;
use App\Models\ActivityWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityWorkflowController extends Controller
{
    /**
     * Handle workflow transitions.
     */
    public function transition(Request $request, Activity $activity)
    {
        $currentWorkflow = $activity->currentWorkflow;

        // Start workflow if not exists
        if (!$currentWorkflow) {
            $currentWorkflow = ActivityWorkflow::create([
                'activity_id' => $activity->id,
                'stage' => 'target_setting',
                'status' => 'pending',
                'assigned_role' => $this->getRoleId('Planner'),
            ]);
        }

        $user = Auth::user();
        $action = $request->input('action'); // submit, approve, reject
        $comments = $request->input('comments');

        // Logic based on current stage
        switch ($currentWorkflow->stage) {
            case 'target_setting':
                if (!$user->hasPermission('activity.set_target')) {
                    abort(403, 'Unauthorized to set targets.');
                }
                if ($action === 'submit') {
                    $this->updateWorkflow($activity, 'execution', 'pending', null, 'Executor');
                }
                break;

            case 'execution':
                if (!$user->hasPermission('activity.execute')) {
                    abort(403, 'Unauthorized to execute.');
                }
                if ($action === 'submit') {
                    // Check if evidence exists (handled in frontend/Separate check)
                    $this->updateWorkflow($activity, 'verification', 'pending', null, 'Quality Assurance');
                }
                break;

            case 'verification':
                if (!$user->hasPermission('activity.verify')) {
                    abort(403, 'Unauthorized to verify.');
                }
                if ($action === 'approve') {
                    $this->updateWorkflow($activity, 'approval', 'pending', null, 'Project Manager');
                } elseif ($action === 'reject') {
                    $this->updateWorkflow($activity, 'execution', 'rejected', null, 'Executor', $comments);
                }
                break;

            case 'approval':
                if (!$user->hasPermission('activity.approve')) {
                    abort(403, 'Unauthorized to approve.');
                }
                if ($action === 'approve') {
                    $this->updateWorkflow($activity, 'completed', 'approved', null, null, 'Activity Completed');
                } elseif ($action === 'reject') {
                    $this->updateWorkflow($activity, 'execution', 'rejected', null, 'Executor', $comments);
                }
                break;
        }

        return back()->with('success', 'Workflow updated successfully.');
    }

    private function updateWorkflow($activity, $stage, $status, $assignedTo = null, $assignedRoleName = null, $comments = null)
    {
        $assignedRoleId = $assignedRoleName ? $this->getRoleId($assignedRoleName) : null;

        ActivityWorkflow::create([
            'activity_id' => $activity->id,
            'stage' => $stage,
            'status' => $status,
            'assigned_to' => $assignedTo,
            'assigned_role' => $assignedRoleId,
            'comments' => $comments,
        ]);
    }

    private function getRoleId($roleName)
    {
        return Role::where('title', $roleName)->value('id');
    }
}
