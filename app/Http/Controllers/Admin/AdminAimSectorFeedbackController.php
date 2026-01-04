<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aim;
use App\Models\AimSectorFeedback;
use App\Models\Sector;

class AdminAimSectorFeedbackController extends Controller
{

    public function index(string $current_year = '2025')
    {
        // Allowed years (can be moved to config later)
        $years = ['2023', '2024', '2025'];

        if (! in_array($current_year, $years, true)) {
            abort(403);
        }

        // Load sectors once
        $sectors = Sector::orderBy('id')
            ->where('cate', 1)
            ->get(['id', 'short_name']);

        // Load aims with feedback for the selected year
        $aims = Aim::with([
            'aimSectorFeedbackValues' => function ($q) use ($current_year) {
                $q->where('current_year', $current_year)
                    ->select('id', 'aim_id', 'sector_id', 'achieved');
            }
        ])->get(['id', 'title']);

        /**
         * Normalize data for the view:
         * - feedbackBySector[sector_id] = achieved
         * - total = sum of achieved values
         */
        $aims->each(function ($aim) use ($sectors) {
            $feedback = $aim->aimSectorFeedbackValues
                ->keyBy('sector_id');

            $aim->feedbackBySector = $sectors->mapWithKeys(function ($sector) use ($feedback) {
                return [
                    $sector->id => $feedback[$sector->id]->achieved ?? 0
                ];
            });

            $aim->total = $aim->feedbackBySector->sum();
        });

        return view('admin.aim_sector_feedback.index', [
            'sectors'      => $sectors,
            'aims'         => $aims,
            'current_year' => $current_year,
            'years'        => $years,
        ]);
    }



    public function show(Aim $aim, Sector $sector)
    {
        $feedback = AimSectorFeedback::where([
            'aim_id'    => $aim->id,
            'sector_id' => $sector->id,
        ])->firstOrFail();

        $user = auth()->user();

        return view('admin.aim_sector_feedback.show', compact('feedback'));
    }
}
