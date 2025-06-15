<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller
{
    public function logout()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();
        return back();
    }

    public function dashboard()
    {
        $loggedUser = auth()->user();

        $indicators = Indicator::all();
        // return view('dashboard_sector',compact('indicators'));
        if($loggedUser->user_type == 'sector'){
        }

        return view('dashboard',compact('indicators'));
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
