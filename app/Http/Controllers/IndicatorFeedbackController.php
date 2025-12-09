<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\IndicatorFeedbackValue;
use App\Models\Sector;
use Illuminate\Http\Request;

class IndicatorFeedbackController extends Controller
{
    // ------------------------------------------------------------------
    // INDEX
    // ------------------------------------------------------------------
    public function index(Indicator $indicator)
    {

        $user = auth()->user();

        $userSector = $user->sectors()->first();

        if (!$userSector) {
            abort(404);
        }
        $feedbacks = IndicatorFeedbackValue::where('indicator_id', $indicator->id)
            ->where('sector_id', $userSector->id)
            ->get();

        return view('indicator_feedback_value.index', compact('indicator','feedbacks','userSector'));
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
    public function create(Indicator $indicator)
    {
        $years = ['2023', '2024', '2025'];

        $user = auth()->user();

        $sector = $user->sectors()->first();

        return view('indicator_feedback_value.create', compact(
            'indicator',
            'years',
            'sector'
        ));
    }

    // ------------------------------------------------------------------
    // STORE
    // ------------------------------------------------------------------
    public function store(Request $request, Indicator $indicator)
    {
        $request->validate([
            'achieved' => 'required|integer|min:0',
            'evidence_title' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:1000',
            'current_year' => 'required|in:2023,2024,2025',
            'evidence_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $user = auth()->user();
        $userSector = $user->sectors()->first();

        $fileUrl = null;

        if ($request->hasFile('evidence_file')) {
            $file = $request->file('evidence_file');
            $fileName = 'indicator_' . $indicator->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $fileUrl = $file->storeAs('indicator_feedback/indicator_evidence', $fileName, 'public');
        }

        IndicatorFeedbackValue::create([
            'indicator_id' => $indicator->id,
            'sector_id' => $userSector->id,
            'achieved' => $request->achieved,
            'evidence_title' => $request->evidence_title,
            'note' => $request->note,
            'evidence_url' => $fileUrl,
            'current_year' => $request->current_year,
            'createdby_user_id' => $user->id,
        ]);

        return redirect()->route('indicator_feedback_value.index', $indicator)
            ->with('success', 'تم إضافة التغذية الراجعة بنجاح');
    }

    // ------------------------------------------------------------------
    // EDIT
    // ------------------------------------------------------------------
    public function edit(IndicatorFeedbackValue $feedback)
    {
        $years = ['2023', '2024', '2025'];
        $user = auth()->user();

        abort_if($feedback->createdby_user_id !== $user->id, 403);

        return view('indicator_feedback_value.edit', compact(
            'feedback',
            'years'
        ));
    }

    // ------------------------------------------------------------------
    // UPDATE
    // ------------------------------------------------------------------
    public function update(Request $request, IndicatorFeedbackValue $feedback)
    {
        $request->validate([
            'achieved' => 'required|integer|min:0',
            'evidence_title' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:1000',
            'current_year' => 'required|in:2023,2024,2025',
            'evidence_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        abort_if(auth()->id() !== $feedback->createdby_user_id, 403);

        $fileUrl = $feedback->evidence_url;

        if ($request->hasFile('evidence_file')) {
            $file = $request->file('evidence_file');
            $fileName = 'indicator_' . $feedback->indicator_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $fileUrl = $file->storeAs('indicator_feedback/indicator_evidence', $fileName, 'public');
        }

        $feedback->update([
            'achieved' => $request->achieved,
            'evidence_title' => $request->evidence_title,
            'note' => $request->note,
            'evidence_url' => $fileUrl,
            'current_year' => $request->current_year,
        ]);

        return redirect()->route('indicator_feedback_value.show', $feedback)
            ->with('success', 'تم تعديل التغذية الراجعة بنجاح');
    }
}
