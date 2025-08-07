<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;

class StatisticController extends Controller
{

    public function index()
    {
        return view('statistic.index');
    }

    public function quran()
    {
        return view('statistic.quran');
    }
}
