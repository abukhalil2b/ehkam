<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\IndicatorFeedbackValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class IndicatorFeedbackController extends Controller
{

    private function getYears()
    {
        return ['2023', '2024', '2025'];
    }
    // ------------------------------------------------------------------
    // INDEX
    // ------------------------------------------------------------------
    public function index(Indicator $indicator)
    {

        $years = $this->getYears();

        $user = auth()->user();

        $userSector = $user->sectors()->first();

        if (!$userSector) {
            abort(404);
        }
        $feedbacks = IndicatorFeedbackValue::where('indicator_id', $indicator->id)
            ->where('sector_id', $userSector->id)
            ->get();

        return view('indicator_feedback_value.index', compact('indicator', 'feedbacks', 'userSector', 'years'));
    }

    // ------------------------------------------------------------------
    // SHOW
    // ------------------------------------------------------------------
    public function show(IndicatorFeedbackValue $feedback)
    {
        $user = auth()->user();

        // Security: User can only view their own sector feedback
        abort_if($feedback->createdby_user_id !== $user->id, 403);

        return view('indicator_feedback_value.show', compact('feedback'));
    }

    // ------------------------------------------------------------------
    // CREATE
    // ------------------------------------------------------------------
    public function create(Indicator $indicator, $current_year)
    {
        $years = $this->getYears();

        if (!in_array($current_year, $years)) {
            abort(404);
        }

        $user = auth()->user();

        $sector = $user->sectors()->first();

        if (!$sector) {
            abort(404);
        }

        // 3. RETRIEVE EXISTING DATA (The key enhancement)
        // Check if a record already exists for this Indicator, Year, and Sector.
        // This allows the single view to act as both 'create' and 'edit'.
        $feedbackValue = IndicatorFeedbackValue::where('indicator_id', $indicator->id)
            ->where('current_year', $current_year)
            ->where('sector_id', $sector->id) // Assuming sector_id is part of the unique key
            ->first();

        // 4. Pass all necessary variables to the view
        return view('indicator_feedback_value.create', compact(
            'indicator',
            'current_year',
            'sector',
            'feedbackValue' // <-- The existing record (or null)
        ));
    }

    // ------------------------------------------------------------------
    // STORE
    // ------------------------------------------------------------------
    public function store(Request $request, Indicator $indicator)
    {
        $allowedYears = $this->getYears(); // Get the array of allowed years (e.g., [2023, 2024, 2025])

        $request->validate([
            'current_year' => ['required', Rule::in($allowedYears)],
            'achieved' => 'required|integer|min:0',
            'evidence_title' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:1000',
            'evidence_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $user = auth()->user();

        $userSector = $user->sectors()->first();

        // Use 403 for Forbidden/Authorization failure
        if (!$userSector) {
            abort(403, 'User sector not assigned.');
        }


        // Attributes to search by (The WHERE clause)
        $searchAttributes = [
            'indicator_id' => $indicator->id,
            'current_year' => $request->current_year,
        ];

        //Attempt to find the existing record
        $existingFeedback = IndicatorFeedbackValue::where($searchAttributes)->first();

        $fileUrl = null;

        // Handle File Upload and Existing File Logic
        if ($request->hasFile('evidence_file')) {
            // A new file was uploaded: Store it and set the URL
            $file = $request->file('evidence_file');
            $fileName = 'indicator_' . $indicator->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $fileUrl = $file->storeAs('indicator_feedback/indicator_evidence', $fileName, 'public');

            // Optional: Delete the old file if one exists
            if ($existingFeedback && $existingFeedback->evidence_url) {

                Storage::disk('public')->delete($existingFeedback->evidence_url);
            }
        } elseif ($existingFeedback) {
            // No new file uploaded, but a record exists: Preserve the old file URL
            $fileUrl = $existingFeedback->evidence_url;
        }


        // 3. Define the values to update/create
        $updateValues = [
            'sector_id' => $userSector->id,
            'achieved' => $request->achieved,
            'evidence_title' => $request->evidence_title,
            'note' => $request->note,
            // Use the determined $fileUrl (new, old, or null)
            'evidence_url' => $fileUrl,
            'createdby_user_id' => $user->id,
        ];

        // 4. Execute updateOrCreate
        $indicatorFeedback = IndicatorFeedbackValue::updateOrCreate(
            $searchAttributes,
            $updateValues
        );

        // 4. Return
        return redirect()->route('indicator_feedback_value.index', $indicator)
            ->with('success', 'تم إضافة التغذية الراجعة بنجاح');
    }
}
