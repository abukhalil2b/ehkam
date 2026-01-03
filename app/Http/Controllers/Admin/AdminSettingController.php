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

    public function copyYear(int $year)
    {
        $fromYear = $year - 1;

        // 1. Get indicators from last year
        $oldIndicators = Indicator::where('current_year', $fromYear)->get();

        if ($oldIndicators->isEmpty()) {
            return back()->with('error', 'لا توجد مؤشرات في السنة السابقة');
        }

        // Prevent duplicate copy
        $alreadyExists = Indicator::where('current_year', $year)->exists();
        if ($alreadyExists) {
            return back()->with('error', 'تم إنشاء بيانات هذه السنة مسبقاً');
        }

        $indicatorMap = [];

        DB::transaction(function () use ($oldIndicators, $year, &$indicatorMap) {

            // 2. Copy indicators
            foreach ($oldIndicators as $oldIndicator) {
                $newIndicator = $oldIndicator->replicate();
                $newIndicator->current_year = $year;
                $newIndicator->save();

                // Map old indicator ID → new indicator ID
                $indicatorMap[$oldIndicator->id] = $newIndicator->id;
            }

            // 3. Copy projects
            $oldProjects = Project::whereIn(
                'indicator_id',
                array_keys($indicatorMap)
            )->get();

            foreach ($oldProjects as $oldProject) {
                $newProject = $oldProject->replicate();
                $newProject->indicator_id = $indicatorMap[$oldProject->indicator_id];
                $newProject->save();
            }
        });

        return redirect()
            ->route('admin_setting.indicator.index', ['year' => $year])
            ->with('success', 'تم نسخ المؤشرات والمشاريع للسنة الجديدة بنجاح');
    }


    public function indicatorIndex(int $year)
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

        $indicators = Indicator::query()
            ->where('current_year', $year)
            ->orderBy('title')
            ->get();

        return view('admin_setting.indicator.index', [
            'indicators'     => $indicators,
            'selectedYear'   => $year,
            'availableYears' => $availableYears,
        ]);
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
