<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceNeed;
use App\Models\Indicator;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSettingController extends Controller
{



    public function indicatorIndex()
    {

        $indicators = Indicator::all();

        return view('admin_setting.indicator.index', compact('indicators'));
    }

    public function projectIndex(int $year)
    {
        $availableYears = Indicator::query()
            ->select('current_year')
            ->distinct()
            ->pluck('current_year')
            ->toArray();

        // Fallback to most recent year if invalid year is requested
        if (!in_array($year, $availableYears)) {
            $year = $availableYears[0] ?? now()->year;
        }

        $indicatorIds = Indicator::query()
            ->where('current_year', $year)
            ->pluck('id');

        $projects = Project::with('indicator')
            ->whereIn('indicator_id', $indicatorIds)
            ->orderBy('title')
            ->get();

        return view('admin_setting.project.index', [
            'projects'       => $projects,
            'selectedYear'   => $year,
            'availableYears' => $availableYears,
        ]);
    }
}
