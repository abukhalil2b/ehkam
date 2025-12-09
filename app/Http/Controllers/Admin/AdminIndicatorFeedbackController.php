<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Indicator;
use App\Models\Sector;

class AdminIndicatorFeedbackController extends Controller
{

    public function index($current_year = '2025')
    {
        if (!in_array($current_year, ['2023', '2024', '2025'])) {
            abort(403);
        }

        // Get all sectors
        $sectors = Sector::orderBy('id')->get();

        // Get all indicators with their feedback values for this year
        $indicators = Indicator::with(['indicatorFeedbackValues' => function ($q) use ($current_year) {
            $q->where('current_year', $current_year);
        }])->get();

        return view('admin.indicator_feedback_value.index', compact(
            'sectors',
            'indicators',
            'current_year'
        ));
    }
}
