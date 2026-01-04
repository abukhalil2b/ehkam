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

        $tasks = Task::where('assigned_to' , $loggedUser->id)->get();

        if ($sector) {

            $aims = Aim::all();

            return view('dashboard_sector', compact('tasks', 'sector', 'aims'));
        }

        return view('dashboard', compact('tasks'));
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
