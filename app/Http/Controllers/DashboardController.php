<?php

namespace App\Http\Controllers;

use App\Models\Aim;
use App\Models\Indicator;
use App\Models\Sector;
use App\Models\Step;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{


    public function dashboard()
    {
        $loggedUser = auth()->user();

        $tasks = Task::where('assigned_to', $loggedUser->id)->get();

        // 1. Step Status/Phase Stats
        $stepsByPhase = Step::select('phase', DB::raw('count(*) as total'))
            ->groupBy('phase')
            ->pluck('total', 'phase')
            ->toArray();

        // Ensure all phases have values
        $phases = ['planning', 'implementation', 'review', 'close'];
        $chartData = [];
        foreach ($phases as $phase) {
            $chartData[] = $stepsByPhase[$phase] ?? 0;
        }

        // 2. Active Alerts (Steps approaching end date)
        $activeAlerts = Step::whereDate('end_date', '<', now())
            ->whereDate('end_date', '>=', now()->subDays(7))
            ->count();

        // 3. Project Health (Based on steps with end dates)
        $totalSteps = Step::whereNotNull('end_date')->count();
        $stepsOnTime = Step::whereNotNull('end_date')
            ->whereDate('end_date', '>=', now())
            ->count();
        $healthPercentage = $totalSteps > 0 ? round(($stepsOnTime / $totalSteps) * 100) : 100;

        // 4. Recent Notifications
        $recentNotifications = $loggedUser->notifications()->latest()->take(3)->get();

        // 5. Task Priorities for Chart
        $taskPriorities = [
            $tasks->where('priority', 'high')->count(),
            $tasks->where('priority', 'medium')->count(),
            $tasks->where('priority', 'low')->count()
        ];

        // 6. Pending Workflows (My Pending Items)
        // Note: Steps don't have workflows, only activities do
        // TODO: Implement pendingWorkflowActivities() in User model if needed
        $myWorkflows = collect();

        // Role-based dashboard selection
        $activeRole = $loggedUser->getActiveRole();

        if ($activeRole && $activeRole->slug === 'sector') {
            $sector = $loggedUser->sectors()->first();
            $aims = Aim::all();
            return view('dashboard_sector', compact('tasks', 'sector', 'aims', 'chartData', 'activeAlerts', 'healthPercentage', 'recentNotifications', 'taskPriorities', 'myWorkflows', 'activeRole'));
        }

        return view('dashboard', compact('tasks', 'chartData', 'activeAlerts', 'healthPercentage', 'recentNotifications', 'taskPriorities', 'myWorkflows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Indicator $indicator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Indicator $indicator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Indicator $indicator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Indicator $indicator)
    {
        //
    }
}
