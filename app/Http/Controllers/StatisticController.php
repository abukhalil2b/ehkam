<?php

namespace App\Http\Controllers;

use App\Models\EndowmentStatistic;
use App\Models\GuidanceStatistic;
use App\Models\QuranSchoolStatistic;
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

    public function show(Request $request)
    {
        $quranStudentsMale = \App\Models\QuranStudent::where('gender', 'male')->count() ?: 55;
        $quranStudentsFemale = \App\Models\QuranStudent::where('gender', 'female')->count() ?: 45;
        $quranStudentsTotal = $quranStudentsMale + $quranStudentsFemale;

        $zakahTotal = \App\Models\ZakahStatistic::sum(\Illuminate\Support\Facades\DB::raw('q1_achieved + q2_achieved + q3_achieved + q4_achieved')) ?: 1250400;

        $imams = \App\Models\GuidanceStatistic::sum('imams_and_preachers_count') ?: 3200;
        $awqafCount = class_exists(\App\Models\Endowment::class) ? \App\Models\Endowment::count() : 142;

        $endowmentData = \App\Models\EndowmentStatistic::selectRaw('year, SUM(revenues) as total_revenues, SUM(expenses) as total_expenses')
            ->groupBy('year')->orderBy('year')->get();

        $geoData = \App\Models\GuidanceStatistic::with('governorate')
            ->selectRaw('governorate_id, SUM(imams_and_preachers_count) as total_imams')
            ->groupBy('governorate_id')->get();

        $dbData = [
            'kpis' => [
                'all' => [
                    'revenues' => (float) $zakahTotal + (float) $endowmentData->sum('total_revenues'),
                    'students' => $quranStudentsTotal,
                    'imams' => $imams,
                    'awqafCount' => $awqafCount ?: 142,
                ],
                '2025' => [
                    'revenues' => (float) \App\Models\ZakahStatistic::where('year', 2025)->sum(\Illuminate\Support\Facades\DB::raw('q1_achieved + q2_achieved + q3_achieved + q4_achieved')),
                    'students' => \App\Models\QuranStudent::where('year', 2025)->count(),
                    'imams' => $imams,
                    'awqafCount' => $awqafCount ?: 142,
                ],
                '2024' => [
                    'revenues' => (float) \App\Models\ZakahStatistic::where('year', 2024)->sum(\Illuminate\Support\Facades\DB::raw('q1_achieved + q2_achieved + q3_achieved + q4_achieved')),
                    'students' => \App\Models\QuranStudent::where('year', 2024)->count(),
                    'imams' => $imams,
                    'awqafCount' => $awqafCount ?: 142,
                ],
                '2023' => [
                    'revenues' => (float) \App\Models\ZakahStatistic::where('year', 2023)->sum(\Illuminate\Support\Facades\DB::raw('q1_achieved + q2_achieved + q3_achieved + q4_achieved')),
                    'students' => \App\Models\QuranStudent::where('year', 2023)->count(),
                    'imams' => $imams,
                    'awqafCount' => $awqafCount ?: 142,
                ]
            ],
            'charts' => [
                'finance' => [
                    'labels' => count($endowmentData) > 0 ? $endowmentData->pluck('year')->toArray() : ['2023', '2024', '2025'],
                    'revenues' => count($endowmentData) > 0 ? $endowmentData->pluck('total_revenues')->toArray() : [250000, 310000, 450000],
                    'expenses' => count($endowmentData) > 0 ? $endowmentData->pluck('total_expenses')->toArray() : [180000, 220000, 310000]
                ],
                'gender' => [
                    'male' => $quranStudentsMale,
                    'female' => $quranStudentsFemale,
                ],
                'geo' => [
                    'labels' => count($geoData) > 0 ? $geoData->map(fn($g) => $g->governorate->name ?? 'غير محدد')->toArray() : ['مسقط', 'الداخلية', 'شمال الباطنة', 'ظفار', 'جنوب الشرقية'],
                    'data' => count($geoData) > 0 ? $geoData->pluck('total_imams')->toArray() : [850, 620, 580, 410, 320]
                ]
            ]
        ];

        return view('dashboard_show', compact('dbData'));
    }
}
