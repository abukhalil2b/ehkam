<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\IndicatorWorkflow;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndicatorWorkflowController extends Controller
{
    /**
     * Handle workflow transitions.
     */
    public function transition(Request $request, Indicator $indicator)
    {
        $currentWorkflow = $indicator->currentWorkflow;

        // Start workflow if not exists
        if (!$currentWorkflow) {
            $currentWorkflow = IndicatorWorkflow::create([
                'indicator_id' => $indicator->id,
                'stage' => 'reporting',
                'status' => 'pending',
                'assigned_role' => $this->getRoleId('Planner'), // or Executor
            ]);
        }

        $user = Auth::user();
        $action = $request->input('action'); // submit, approve, reject
        $comments = $request->input('comments');

        // Logic based on current stage
        switch ($currentWorkflow->stage) {
            case 'reporting':
                if (!$user->hasPermission('indicator.report')) {
                    abort(403, 'Unauthorized to report.');
                }
                if ($action === 'submit') {
                    $this->updateWorkflow($indicator, 'verification', 'pending', null, 'Quality Assurance');
                }
                break;

            case 'verification':
                if (!$user->hasPermission('indicator.verify')) {
                    abort(403, 'Unauthorized to verify.');
                }
                if ($action === 'approve') {
                    $this->updateWorkflow($indicator, 'approval', 'pending', null, 'Project Manager');
                } elseif ($action === 'reject') {
                    $this->updateWorkflow($indicator, 'reporting', 'rejected', null, 'Planner', $comments);
                }
                break;

            case 'approval':
                if (!$user->hasPermission('indicator.approve')) {
                    abort(403, 'Unauthorized to approve.');
                }
                if ($action === 'approve') {
                    $this->updateWorkflow($indicator, 'completed', 'approved', null, null, 'Indicator Achievements Approved');
                } elseif ($action === 'reject') {
                    $this->updateWorkflow($indicator, 'reporting', 'rejected', null, 'Planner', $comments);
                }
                break;
        }

        return back()->with('success', 'Workflow updated successfully.');
    }

    private function updateWorkflow($indicator, $stage, $status, $assignedTo = null, $assignedRoleName = null, $comments = null)
    {
        $assignedRoleId = $assignedRoleName ? $this->getRoleId($assignedRoleName) : null;

        IndicatorWorkflow::create([
            'indicator_id' => $indicator->id,
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
