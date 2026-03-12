<?php

namespace App\Http\Controllers;

use App\Models\Step;
use App\Models\StepEvidenceFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EvidenceFileController extends Controller
{
    /**
     * User 3 (executor) uploads an evidence file for a step.
     */
    public function store(Request $request, Step $step)
    {
        $request->validate([
            'evidence_file' => 'required|file|max:20480|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
        ]);

        $file = $request->file('evidence_file');
        $originalName = $file->getClientOriginalName();
        $storedPath = $file->store("evidence/step_{$step->id}", 'public');

        StepEvidenceFile::create([
            'step_id'     => $step->id,
            'uploaded_by' => Auth::id(),
            'file_path'   => $storedPath,
            'file_name'   => $originalName,
            'status'      => 'pending',
        ]);

        return redirect()->back()->with('success', 'تم رفع ملف الإثبات بنجاح، وهو قيد المراجعة.');
    }

    /**
     * User 2 (approver) approves or returns an evidence file.
     */
    public function review(Request $request, StepEvidenceFile $file)
    {
        $request->validate([
            'action'         => 'required|in:approve,return',
            'reviewer_notes' => 'nullable|string|max:1000',
        ]);

        $file->update([
            'status'         => $request->action === 'approve' ? 'approved' : 'returned',
            'reviewer_notes' => $request->reviewer_notes,
            'reviewed_by'    => Auth::id(),
            'reviewed_at'    => now(),
        ]);

        $message = $request->action === 'approve'
            ? 'تم قبول ملف الإثبات بنجاح.'
            : 'تمت إعادة ملف الإثبات مع الملاحظات.';

        return redirect()->back()->with(
            $request->action === 'approve' ? 'success' : 'info',
            $message
        );
    }

    /**
     * Download / stream an evidence file.
     */
    public function download(StepEvidenceFile $file)
    {
        $fullPath = storage_path('app/public/' . $file->file_path);

        if (!file_exists($fullPath)) {
            abort(404, 'الملف غير موجود.');
        }

        return response()->download($fullPath, $file->file_name);
    }
}
