<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\PeriodTemplate;
use App\Models\Sector;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IndicatorController extends Controller
{

    public function target(Indicator $indicator)
    {
        // return $indicator;
        return view('indicator.target');
    }

    public function achieved(Indicator $indicator)
    {
        return view('indicator.achieved');
    }

    public function index()
    {
        $indicators = Indicator::all();
        
        return view('indicator.index', compact('indicators'));
    }

    public function show(Indicator $indicator)
    {
        // 1. Get the names of the associated sectors
        // Assuming the 'sectors' column stores a JSON array of Sector IDs
        $selectedSectorIds = json_decode($indicator->sectors, true) ?? [];
        $selectedSectors = Sector::whereIn('id', $selectedSectorIds)->pluck('name')->toArray();

        // 3. Get any sub-indicators (children)
        // These are indicators where the parent_id is the current indicator's ID
        $subIndicators = Indicator::where('parent_id', $indicator->id)->get(['id', 'title', 'code']);

        return view('indicator.show', compact('indicator', 'selectedSectors', 'subIndicators'));
    }

    public function create()
    {
        $sectors = Sector::all(); // Need sectors for multi-select field

        return view('indicator.create', compact('sectors'));
    }

    private function getMonthMap()
    {
        // Centralized map for Arabic month names
        return [
            'يناير' => 'January',
            'فبراير' => 'February',
            'مارس' => 'March',
            'أبريل' => 'April',
            'مايو' => 'May',
            'يونيو' => 'June',
            'يوليو' => 'July',
            'أغسطس' => 'August',
            'سبتمبر' => 'September',
            'أكتوبر' => 'October',
            'نوفمبر' => 'November',
            'ديسمبر' => 'December',
        ];
    }

    private function validateAndPrepareData(Request $request)
    {
        $validated = $request->validate([
            'target_for_indicator' => 'nullable|numeric',
            'main_criteria' => 'nullable|string',
            'sub_criteria' => 'nullable|string',
            'code' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'owner' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'measurement_tool' => 'nullable|string',
            'polarity' => 'nullable|string|max:255',
            'polarity_description' => 'nullable|string',
            'unit' => 'nullable|string|max:255',
            'formula' => 'nullable|string|max:255',
            'first_observation_date' => 'nullable|date',
            'baseline_formula' => 'nullable|string',
            'baseline_after_application' => 'nullable|string|max:255',
            'survey_question' => 'nullable|string',
            'proposed_initiatives' => 'nullable|string',
            'period' => 'required|string|max:11',
            'sectors' => 'nullable|array',
            'parent_id' => 'nullable|exists:indicators,id',
        ]);

        // --- Date Handling (Simplified) ---
        // The value is already in 'YYYY-MM-DD' format if valid, so no complex parsing is needed.
        // We ensure it is cast to a string or null if empty.
        $validated['first_observation_date'] = $validated['first_observation_date'] ?? null;

        // --- Sector Array Handling ---
        $validated['sectors'] = json_encode($validated['sectors'] ?? []);

        return $validated;
    }

    public function store(Request $request)
    {
        // return $request->all();
        $validatedData = $this->validateAndPrepareData($request);

        Indicator::create($validatedData);

        return redirect()->route('indicator.index')->with('success', ' تم اضافة المؤشر بنجاح.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Indicator $indicator)
    {
        $sectors = Sector::all();

        // Decode the stored sector IDs for the multi-select field
        $selectedSectorIds = json_decode($indicator->sectors, true) ?? [];

        $periodOptions = [
            'annually'    => 'سنوي',
            'half_yearly' => 'نصف سنوي',
            'quarterly'   => 'ربع سنوي',
            'monthly'     => 'شهري',
        ];

        return view('indicator.edit', compact(
            'indicator',
            'sectors',
            'selectedSectorIds',
            'periodOptions'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Indicator $indicator)
    {
        $validatedData = $this->validateAndPrepareData($request);

        $indicator->update($validatedData);

        return redirect()->route('indicator.show', $indicator)->with('success', ' تم تحديث المؤشر بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Indicator $indicator)
    {
        $indicator->delete();

        return redirect()->route('indicator.index')->with('success', ' تم حذف المؤشر بنجاح.');
    }
}
