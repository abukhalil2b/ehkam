<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceNeed;
use App\Models\visionItem;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAnnualPlanController extends Controller
{

    public function show($year = 2026)
    {
        // جلب المشاريع مع المؤشرات المرتبطة بها وعناصر الرؤية والمستهدفات للسنة المحددة
        $projects = Project::with([
            'indicator.visionItem.parent',
            // جلب المستهدفات لنفس السنة
            'indicator.targets' => function ($q) use ($year) {
                $q->where('year', $year);
            },
            // جلب القيم المتحققة لنفس السنة
            'indicator.achieved' => function ($q) use ($year) {
                $q->where('year', $year);
            }
        ])
            ->whereHas('indicator') // جلب المشاريع التي لها مؤشرات فقط
            ->get();

        return view('admin.annual_plan.show', compact('projects', 'year'));
    }
}
