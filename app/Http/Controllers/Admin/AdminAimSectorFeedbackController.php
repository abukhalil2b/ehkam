<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aim;
use App\Models\AimSectorFeedback;
use App\Models\Sector;

class AdminAimSectorFeedbackController extends Controller
{

    public function index($current_year = '2025')
    {
        $years = ['2023', '2024', '2025'];

        if (!in_array($current_year, $years)) {
            abort(403);
        }

        $sectors = Sector::orderBy('id')->get();

        $aims = Aim::with(['aimSectorFeedbackValues' => function ($q) use ($current_year) {
            $q->where('current_year', $current_year);
        }])->get();

        return view('admin.aim_sector_feedback.index', compact(
            'sectors',
            'aims',
            'current_year',
            'years'
        ));
    }


    public function show(Aim $aim, Sector $sector)
    {
        $feedback = AimSectorFeedback::where('indicator_id', $aim->id)->where('sector_id', $sector->id)->first();

        if (!$feedback) {
            abort(404);
        }
        $user = auth()->user();

        return view('admin.aim_sector_feedback.show', compact('feedback'));
    }
}
