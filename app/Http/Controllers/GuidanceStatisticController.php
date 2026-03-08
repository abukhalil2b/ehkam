<?php

namespace App\Http\Controllers;

use App\Models\GuidanceStatistic;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GuidanceStatisticController extends Controller
{
    public function index()
    {
        $statistics = GuidanceStatistic::with(['governorate', 'wilayat'])
            ->orderBy('year', 'desc')
            ->paginate(15);

        return view('statistic.guidance.index', compact('statistics'));
    }

    public function create()
    {
        // جلب المحافظات مع ولاياتها لاستخدامها في Alpine.js
        $governorates = Governorate::with('wilayats')->get();
        return view('statistic.guidance.create', compact('governorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'wilayat_id' => 'nullable|exists:wilayats,id',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'imams_and_preachers_count' => 'required|integer|min:0',
            'muezzins_count' => 'required|integer|min:0',
            'mentors_male' => 'required|integer|min:0',
            'mentors_female' => 'required|integer|min:0',
            'preachers_male' => 'required|integer|min:0',
            'preachers_female' => 'required|integer|min:0',
            'religious_guides_male' => 'required|integer|min:0',
            'religious_guides_female' => 'required|integer|min:0',
            'supervisors_male' => 'required|integer|min:0',
            'supervisors_female' => 'required|integer|min:0',
        ]);

        GuidanceStatistic::updateOrCreate(
            [
                'governorate_id' => $validated['governorate_id'],
                'wilayat_id' => $validated['wilayat_id'],
                'year' => $validated['year'],
            ],
            $validated
        );

        return redirect()->route('guidance-statistics.index')->with('success', 'تم حفظ بيانات الوعظ والإرشاد بنجاح');
    }
}
