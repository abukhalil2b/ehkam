<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\PeriodTemplate;
use App\Models\YearStatement;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{

    public function index()
    {
        $indicators = Indicator::all();
        return view('indicator.index', compact('indicators'));
    }

    public function create()
    {
        $period_templates = PeriodTemplate::all();

        return view('indicator.create', compact('period_templates'));
    }

    public function store(Request $request)
    {

        $year_statement = YearStatement::latest('id')->first();

        if (!$year_statement) abort(404);

        // return $request->all();
        $validated = $request->validate([
            'target_for_indicator' => 'string',
            'main_criteria' => 'string',
            'sub_criteria' => 'nullable|string',
            'code' => 'nullable',
            'title' => 'required|string',
            'owner' => 'nullable',
            'description' => 'nullable|string',
            'measurement_tool' => 'nullable|string',
            'polarity' => 'nullable|string',
            'polarity_description' => 'nullable|string',
            'unit' => 'nullable|string',
            'formula' => 'nullable|string',
            'first_observation_date' => 'nullable|string',
            'baseline_formula' => 'nullable|string',
            'baseline_after_application' => 'nullable|string',
            'survey_question' => 'nullable|string',
            'proposed_initiatives' => 'nullable|string',
            'period' => 'required',
        ]);

        $validated['year_statement_id'] = $year_statement->id;

        Indicator::create($validated);

        return redirect()->route('indicator.index')->with('success', 'Indicator created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Indicator $indicator)
    {
        return view('indicator.show', compact('indicator'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Indicator $indicator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Indicator $indicator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Indicator $indicator)
    {
        //
    }
}
