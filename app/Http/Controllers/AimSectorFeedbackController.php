<?php

namespace App\Http\Controllers;

use App\Models\Aim;
use App\Models\AimSectorFeedback;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class AimSectorFeedbackController extends Controller
{

    private function getYears()
    {
        return ['2023', '2024', '2025'];
    }
    // ------------------------------------------------------------------
    // INDEX
    // ------------------------------------------------------------------
    public function index(Aim $aim)
    {

        $years = $this->getYears();

        $user = auth()->user();

        $userSector = $user->sectors()->first();

        if (!$userSector) {
            abort(404);
        }
        $feedbacks = AimSectorFeedback::where('aim_id', $aim->id)
            ->where('sector_id', $userSector->id)
            ->get();

        return view('aim_sector_feedback.index', compact('aim', 'feedbacks', 'userSector', 'years'));
    }

    // ------------------------------------------------------------------
    // SHOW
    // ------------------------------------------------------------------
    public function show(AimSectorFeedback $feedback)
    {
        $user = auth()->user();

        // Security: User can only view their own sector feedback
        abort_if($feedback->createdby_user_id !== $user->id, 403);

        return view('aim_sector_feedback.show', compact('feedback'));
    }

    // ------------------------------------------------------------------
    // CREATE
    // ------------------------------------------------------------------
    public function create(Aim $aim, $current_year)
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
        // Check if a record already exists for this Aim, Year, and Sector.
        // This allows the single view to act as both 'create' and 'edit'.
        $feedbackValue = AimSectorFeedback::where('aim_id', $aim->id)
            ->where('current_year', $current_year)
            ->where('sector_id', $sector->id) // Assuming sector_id is part of the unique key
            ->first();

        // 4. Pass all necessary variables to the view
        return view('aim_sector_feedback.create', compact(
            'aim',
            'current_year',
            'sector',
            'feedbackValue' // <-- The existing record (or null)
        ));
    }

    // ------------------------------------------------------------------
    // STORE
    // ------------------------------------------------------------------
    public function store(Request $request, Aim $aim)
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
            'aim_id' => $aim->id,
            'current_year' => $request->current_year,
        ];

        //Attempt to find the existing record
        $existingFeedback = AimSectorFeedback::where($searchAttributes)->first();

        $fileUrl = null;

        // Handle File Upload and Existing File Logic
        if ($request->hasFile('evidence_file')) {
            // A new file was uploaded: Store it and set the URL
            $file = $request->file('evidence_file');
            $fileName = 'indicator_' . $aim->id . '_' . time() . '.' . $file->getClientOriginalExtension();
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
        $indicatorFeedback = AimSectorFeedback::updateOrCreate(
            $searchAttributes,
            $updateValues
        );

        // 4. Return
        return redirect()->route('aim_sector_feedback.index', $aim)
            ->with('success', 'تم إضافة التغذية الراجعة بنجاح');
    }
}
