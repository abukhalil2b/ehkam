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

    public function quran($id)
    {
        return view("statistic.quran.{$id}");
    }

    public function zakah($id)
    {
        return view("statistic.zakah.{$id}");
    }

}
