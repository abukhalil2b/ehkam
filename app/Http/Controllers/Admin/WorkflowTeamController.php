<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkflowTeam;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class WorkflowTeamController extends Controller
{
    /**
     * Display a listing of workflow teams.
     */
    public function index()
    {
        $teams = WorkflowTeam::withCount('users', 'stages')->get();

        return view('admin.workflow.teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new workflow team.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('admin.workflow.teams.create', compact('users'));
    }

    /**
     * Store a newly created workflow team.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $team = WorkflowTeam::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->filled('user_ids')) {
            $team->users()->attach($request->user_ids);
            $this->ensureWorkflowAccess($request->user_ids);
        }

        return redirect()
            ->route('admin.workflow.teams.index')
            ->with('success', 'تم إنشاء الفريق بنجاح');
    }

    /**
     * Display the specified workflow team.
     */
    public function show(WorkflowTeam $team)
    {
        $team->load(['users', 'stages.workflow']);
        $pendingItems = $team->pendingItems(); // Get all pending items (Steps, Activities, AppointmentRequests, etc.)

        return view('admin.workflow.teams.show', compact('team', 'pendingItems'));
    }

    /**
     * Show the form for editing the specified workflow team.
     */
    public function edit(WorkflowTeam $team)
    {
        $team->load('users');
        $users = User::orderBy('name')->get();

        return view('admin.workflow.teams.edit', compact('team', 'users'));
    }

    /**
     * Update the specified workflow team.
     */
    public function update(Request $request, WorkflowTeam $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $team->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $team->users()->sync($request->user_ids ?? []);

        if (!empty($request->user_ids)) {
            $this->ensureWorkflowAccess($request->user_ids);
        }

        return redirect()
            ->route('admin.workflow.teams.index')
            ->with('success', 'تم تحديث الفريق بنجاح');
    }

    /**
     * Remove the specified workflow team.
     */
    public function destroy(WorkflowTeam $team)
    {
        // Check if team is used in any stages
        if ($team->stages()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف الفريق لأنه مستخدم في مراحل سير العمل');
        }

        $team->delete();

        return redirect()
            ->route('admin.workflow.teams.index')
            ->with('success', 'تم حذف الفريق بنجاح');
    }

    /**
     * Add a user to the team (AJAX).
     */
    public function addUser(Request $request, WorkflowTeam $team)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $team->users()->syncWithoutDetaching([$request->user_id]);
        $this->ensureWorkflowAccess([$request->user_id]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove a user from the team (AJAX).
     */
    public function removeUser(Request $request, WorkflowTeam $team)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $team->users()->detach($request->user_id);

        return response()->json(['success' => true]);
    }
    /**
     * Ensure users have access to workflow features.
     */
    private function ensureWorkflowAccess($userIds)
    {
        if (empty($userIds))
            return;

        // Find or create the role for workflow members
        $role = Role::firstOrCreate(
            ['slug' => 'workflow_member'],
            ['title' => 'عضو في سير العمل', 'description' => 'Role for users participating in workflows']
        );

        // Ensure this role has the necessary permissions
        $requiredPermissions = [
            'workflow.pending',
            'workflow.history',
            'workflow.submit',
            'workflow.approve', // Assuming basic approval needed
            'workflow.return',
        ];

        foreach ($requiredPermissions as $slug) {
            $permission = Permission::where('slug', $slug)->first();
            if ($permission && !$role->hasPermission($slug)) {
                $role->permissions()->attach($permission->id);
            }
        }

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            // Check if user has permission workflow.pending
            // We use the User model's hasPermission method which checks all roles
            if (!$user->hasPermission('workflow.pending')) {
                // Assign the role
                $user->assignRole($role);
            }
        }
    }
}
