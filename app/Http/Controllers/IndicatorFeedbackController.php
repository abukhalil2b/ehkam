<?php

namespace App\Http\Controllers;

use App\Models\IndicatorFeedbackSector;
use App\Models\IndicatorFeedback;
use Illuminate\Http\Request;

class IndicatorFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($indicator_id)
    {
        $loggedUser = auth()->user();

        //check if loggedUser has permission on this indicator.
        $indicator =  IndicatorFeedbackSector::where(['indicator_id' => $indicator_id, 'user_id' => $loggedUser->id])
            ->join('indicators', 'indicator_feedback_sectors.indicator_id', '=', 'indicators.id')
            ->first();

        if (!$indicator) {
            abort(403);
        }
        
        return $indicator;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IndicatorFeedback $indicatorFeedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IndicatorFeedback $indicatorFeedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IndicatorFeedback $indicatorFeedback)
    {
        //
    }
}
