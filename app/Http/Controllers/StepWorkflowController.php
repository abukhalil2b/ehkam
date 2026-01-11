<?php

namespace App\Http\Controllers;

use App\Models\Step;
use App\Models\StepWorkflow;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StepWorkflowController extends Controller
{
    /**
     * Handle workflow transitions for a Step.
     */
    public function transition(Request $request, Step $step)
    {
        $currentWorkflow = $step->currentWorkflow;

        // Start workflow if not exists
        if (!$currentWorkflow) {
            $currentWorkflow = StepWorkflow::create([
                'step_id' => $step->id,
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
                // Check permissions (Mocking permissions for now based on Activity logic, ideally should be step.set_target)
                if (!$user->hasPermission('activity.set_target')) {
                    // abort(403, 'Unauthorized to set targets.');
                }
                if ($action === 'submit') {
                    $this->updateWorkflow($step, 'execution', 'pending', null, 'Executor');
                }
                break;

            case 'execution':
                if (!$user->hasPermission('activity.execute')) {
                    // abort(403, 'Unauthorized to execute.');
                }
                if ($action === 'submit') {
                    $this->updateWorkflow($step, 'verification', 'pending', null, 'Quality Assurance');
                }
                break;

            case 'verification':
                if (!$user->hasPermission('activity.verify')) {
                    // abort(403, 'Unauthorized to verify.');
                }
                if ($action === 'approve') {
                    $this->updateWorkflow($step, 'approval', 'pending', null, 'Project Manager');
                } elseif ($action === 'reject') {
                    $this->updateWorkflow($step, 'execution', 'rejected', null, 'Executor', $comments);
                }
                break;

            case 'approval':
                if (!$user->hasPermission('activity.approve')) {
                    // abort(403, 'Unauthorized to approve.');
                }
                if ($action === 'approve') {
                    $this->updateWorkflow($step, 'completed', 'approved', null, null, 'Step Completed');
                    // Also update the main Step status
                    $step->update(['status' => 'completed']);
                } elseif ($action === 'reject') {
                    $this->updateWorkflow($step, 'execution', 'rejected', null, 'Executor', $comments);
                }
                break;
        }

        return back()->with('success', 'تم تحديث حالة سير العمل بنجاح.');
    }

    private function updateWorkflow($step, $stage, $status, $assignedTo = null, $assignedRoleName = null, $comments = null)
    {
        $assignedRoleId = $assignedRoleName ? $this->getRoleId($assignedRoleName) : null;

        StepWorkflow::create([
            'step_id' => $step->id,
            'stage' => $stage,
            'status' => $status,
            'assigned_to' => $assignedTo,
            'assigned_role' => $assignedRoleId,
            'comments' => $comments,
        ]);
    }

    private function getRoleId($roleName)
    {
        return Profile::where('title', $roleName)->value('id');
    }
}
