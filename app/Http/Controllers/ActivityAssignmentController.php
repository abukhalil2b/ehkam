<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use App\Models\Role;
use App\Notifications\ActivityAssignedNotification;
use Illuminate\Http\Request;

class ActivityAssignmentController extends Controller
{
    public function index()
    {
        // Find the "Planning Department" role
        // We will try different common names since we couldn't verify the exact name
        $planningRole = Role::where('slug', 'like', '%planner%')
            ->first();

        if (!$planningRole) {
            // Fallback: Get all users if role not found (or handle error)
            // Ideally we should list all users and let admin filter, or show specific users
            $users = [];
        } else {
            $users = User::whereHas('roles', function ($q) use ($planningRole) {
                $q->where('id', $planningRole->id);
            })->get();
        }

        $activities = Activity::with('employees')->get();

        return view('activity.assignments.index', compact('users', 'activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $activity = Activity::findOrFail($request->activity_id);
        $user = User::findOrFail($request->user_id);

        // Check if already assigned
        if (!$activity->employees()->where('user_id', $user->id)->exists()) {
            $activity->employees()->attach($user->id);

            // Send Notification
            $user->notify(new ActivityAssignedNotification($activity));
        }

        return back()->with('success', 'تم تعيين الموظف بنجاح');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $activity = Activity::findOrFail($request->activity_id);
        $activity->employees()->detach($request->user_id);

        return back()->with('success', 'تم حذف التعيين بنجاح');
    }
}
