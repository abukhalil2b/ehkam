<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkflowTeam;
use App\Models\User;
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
        $pendingSteps = $team->pendingSteps()->with(['workflow', 'currentStage'])->get();

        return view('admin.workflow.teams.show', compact('team', 'pendingSteps'));
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
}
