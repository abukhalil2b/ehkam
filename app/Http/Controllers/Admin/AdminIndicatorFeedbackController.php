<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Indicator;
use App\Models\IndicatorFeedbackValue;
use App\Models\Sector;

class AdminIndicatorFeedbackController extends Controller
{

    public function index($current_year = '2025')
    {
        $years = ['2023', '2024', '2025'];

        if (!in_array($current_year, $years)) {
            abort(403);
        }

        $sectors = Sector::orderBy('id')->get();

        $indicators = Indicator::with(['indicatorFeedbackValues' => function ($q) use ($current_year) {
            $q->where('current_year', $current_year);
        }])->get();

        return view('admin.indicator_feedback_value.index', compact(
            'sectors',
            'indicators',
            'current_year',
            'years'
        ));
    }


    public function show(Indicator $indicator, Sector $sector)
    {
        $feedback = IndicatorFeedbackValue::where('indicator_id', $indicator->id)->where('sector_id', $sector->id)->first();

        if (!$feedback) {
            abort(404);
        }
        $user = auth()->user();

        return view('admin.indicator_feedback_value.show', compact('feedback'));
    }
}
