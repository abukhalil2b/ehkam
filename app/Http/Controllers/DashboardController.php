<?php

namespace App\Http\Controllers;

use App\Models\Aim;
use App\Models\Indicator;
use App\Models\Sector;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller
{


    public function dashboard()
    {
        $loggedUser = auth()->user();

        $sector = $loggedUser->sectors()->first();

        $tasks = Task::where('assigned_to', $loggedUser->id)->get();

        // 1. Step Status/Phase Stats
        $stepsByPhase = \App\Models\Step::select('phase', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('phase')
            ->pluck('total', 'phase')
            ->toArray();

        // Ensure all phases have values
        $phases = ['preparation', 'planning', 'implementation', 'review', 'approval'];
        $chartData = [];
        foreach ($phases as $phase) {
            $chartData[] = $stepsByPhase[$phase] ?? 0;
        }

        // 2. Active Alerts (Delayed Steps)
        $activeAlerts = \App\Models\Step::where('status', 'delayed')
            ->orWhere(function ($query) {
                $query->whereDate('end_date', '<', now())
                    ->where('status', '!=', 'completed');
            })->count();

        // 3. Project Health (Completed Percentage)
        $totalSteps = \App\Models\Step::count();
        $completedSteps = \App\Models\Step::where('status', 'completed')->count();
        $healthPercentage = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;

        // 4. Recent Notifications
        $recentNotifications = $loggedUser->notifications()->latest()->take(3)->get();

        // 5. Task Priorities for Chart
        $taskPriorities = [
            $tasks->where('priority', 'high')->count(),
            $tasks->where('priority', 'medium')->count(),
            $tasks->where('priority', 'low')->count()
        ];

        // 6. Pending Workflows (My Workflows)
        $myWorkflows = \App\Models\StepWorkflow::where('status', '!=', 'completed') // Only pending/active
            ->where(function ($q) use ($loggedUser) {
                $q->where('assigned_to', $loggedUser->id);
                // If user has profiles, check roles too
                if ($loggedUser->profiles->isNotEmpty()) {
                    $q->orWhereIn('assigned_role', $loggedUser->profiles->pluck('id'));
                }
            })
            ->with(['step.project']) // Eager load step and its project/mission context if needed
            ->latest()
            ->take(5)
            ->get();

        if ($sector) {
            $aims = Aim::all();
            return view('dashboard_sector', compact('tasks', 'sector', 'aims', 'chartData', 'activeAlerts', 'healthPercentage', 'recentNotifications', 'taskPriorities', 'myWorkflows'));
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
