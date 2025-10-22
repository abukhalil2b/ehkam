<?php

namespace App\Http\Controllers;

use App\Models\MeetingMinute;
use App\Models\MeetingAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MeetingMinuteController extends Controller
{
    // ---------------------- INDEX ----------------------
    public function index()
    {
        $meeting_minutes = MeetingMinute::with('writtenBy', 'attendances')
            ->latest()
            ->paginate(10);

        return view('meeting_minute.index', compact('meeting_minutes'));
    }

    // ---------------------- CREATE ----------------------
    public function create()
    {
        $users = User::all();
        return view('meeting_minute.create', compact('users'));
    }

    // ---------------------- STORE ----------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file_upload' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:5120',
            'attendances' => 'nullable|array',
            'attendances.*' => 'nullable|string|max:255',
        ]);

        // Protect content against XSS
        $validated['content'] = $validated['content']
            ? nl2br(e($validated['content']))
            : null;

        // File upload
        if ($request->hasFile('file_upload')) {
            $validated['file_upload_link'] = $request->file('file_upload')->store('meeting_minutes', 'public');
        }

        // Create main record
        $meetingMinute = MeetingMinute::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'file_upload_link' => $validated['file_upload_link'] ?? null,
            'written_by' => auth()->id(),
        ]);

        // Add attendance names
        if (!empty($validated['attendances'])) {
            foreach ($validated['attendances'] as $name) {
                if (trim($name) !== '') {
                    $meetingMinute->attendances()->create(['name' => e($name)]);
                }
            }
        }

        return redirect()->route('meeting_minute.index')
            ->with('success', 'تم إنشاء محضر الاجتماع بنجاح');
    }

    // ---------------------- SHOW ----------------------
    public function show(MeetingMinute $meeting_minute)
    {
        $meeting_minute->load('writtenBy', 'attendances');
        return view('meeting_minute.show', compact('meeting_minute'));
    }

    // ---------------------- EDIT ----------------------
    public function edit(MeetingMinute $meeting_minute)
    {
        $meeting_minute->load('attendances');
        $users = User::all();
        return view('meeting_minute.edit', compact('meeting_minute', 'users'));
    }

    // ---------------------- UPDATE ----------------------
    public function update(Request $request, MeetingMinute $meeting_minute)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file_upload' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:5120',
            'attendances' => 'nullable|array',
            'attendances.*' => 'nullable|string|max:255',
        ]);

        $validated['content'] = $validated['content']
            ? nl2br(e($validated['content']))
            : null;

        // Replace old file if a new one is uploaded
        if ($request->hasFile('file_upload')) {
            if ($meeting_minute->file_upload_link && Storage::disk('public')->exists($meeting_minute->file_upload_link)) {
                Storage::disk('public')->delete($meeting_minute->file_upload_link);
            }
            $validated['file_upload_link'] = $request->file('file_upload')->store('meeting_minutes', 'public');
        }

        // Update main record
        $meeting_minute->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'file_upload_link' => $validated['file_upload_link'] ?? $meeting_minute->file_upload_link,
            'written_by' => auth()->id(),
        ]);

        // Refresh attendances
        $meeting_minute->attendances()->delete();
        if (!empty($validated['attendances'])) {
            foreach ($validated['attendances'] as $name) {
                if (trim($name) !== '') {
                    $meeting_minute->attendances()->create(['name' => e($name)]);
                }
            }
        }

        return redirect()->route('meeting_minute.index')
            ->with('success', 'تم تحديث محضر الاجتماع بنجاح');
    }

    // ---------------------- DESTROY ----------------------
    public function destroy(MeetingMinute $meeting_minute)
    {
        // Delete attached file if exists
        if ($meeting_minute->file_upload_link && Storage::disk('public')->exists($meeting_minute->file_upload_link)) {
            Storage::disk('public')->delete($meeting_minute->file_upload_link);
        }

        $meeting_minute->delete();

        return redirect()->route('meeting_minute.index')
            ->with('success', 'تم حذف محضر الاجتماع بنجاح');
    }
}
