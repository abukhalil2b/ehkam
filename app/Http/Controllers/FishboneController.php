<?php

namespace App\Http\Controllers;


class FishboneController extends Controller
{
    // Admin: Create new SWOT project
    public function dashboard()
    {
        return view('fishbone.dashboard');
    }
}
