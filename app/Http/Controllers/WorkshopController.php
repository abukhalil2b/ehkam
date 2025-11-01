<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use App\Models\WorkshopAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class WorkshopController extends Controller
{

    public function attendance_register(Request $request)
    {
        // 1. Find the currently active workshop
        $workshop = Workshop::where('is_active', true)->first();

        $qrImage = null;
        $attendances = collect([]); // Initialize empty collection

        // Handle case where no active workshop is found
        if (!$workshop) {
            $message = 'لا توجد ورشة عمل نشطة حاليًا لتسجيل الحضور.';

            if ($request->isMethod('POST')) {
                return back()->with('error', $message);
            }

            // For GET request, pass empty data
            return view('workshow_attendance_register', compact('workshop', 'attendances', 'qrImage'))
                ->with('warning', $message);
        }

        // 2. Handle POST Request (Registration Submission) - Workshop exists
        if ($request->isMethod('POST')) {
            // Validate the incoming data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'job_title' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
            ], [
                'name.required' => 'حقل الاسم مطلوب.',
                'name.string' => 'الاسم يجب أن يكون نصًا.',
                'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرفًا.',
            ]);

            // Check for duplicate registration
            $existingAttendance = WorkshopAttendance::where('workshop_id', $workshop->id)
                ->where('attendee_name', $validated['name'])
                ->first();

            if ($existingAttendance) {
                return back()
                    ->withInput()
                    ->with('warning', 'لقد تم تسجيل حضورك مسبقًا في ورشة العمل هذه.');
            }

            // Create the attendance record
            WorkshopAttendance::create([
                'workshop_id' => $workshop->id,
                'attendee_name' => $validated['name'],
                'job_title' => $validated['job_title'],
                'department' => $validated['department'],
            ]);

            return back()->with('success', 'تم تسجيل حضورك بنجاح! نرحب بك.');
        } else {
            // Generate QR code only if workshop exists
            $qrImage = QrCodeGenerator::size(200)->generate(route('workshow_attendance_register'));
        }

        // 3. Handle GET Request (Display form and list) - Workshop exists
        $attendances = WorkshopAttendance::where('workshop_id', $workshop->id)
            ->latest()
            ->get();

        return view('workshow_attendance_register', compact('attendances', 'workshop', 'qrImage'));
    }

    // ---------------------- INDEX ----------------------
    public function index()
    {
        $workshops = Workshop::with('attendances')
            ->latest()
            ->paginate(10);

        return view('workshop.index', compact('workshops'));
    }

    // ---------------------- CREATE ----------------------
    public function create()
    {
        $users = User::all();
        return view('workshop.create', compact('users'));
    }

    // ---------------------- STORE ----------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Combine start date and time
        $startsAt = $validated['start_date'] . ' ' . $validated['start_time'];

        // Combine end date and time if provided
        $endsAt = null;
        if ($validated['end_date'] && $validated['end_time']) {
            $endsAt = $validated['end_date'] . ' ' . $validated['end_time'];
        }

        Workshop::create([
            'title' => $validated['title'],
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'location' => $validated['location'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'] ?? false,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('workshop.index')
            ->with('success', 'تم إنشاء الورشة بنجاح.');
    }

    // ---------------------- SHOW ----------------------
    public function show(Workshop $workshop)
    {
        $workshop->load(['createdBy', 'attendances']);

        return view('workshop.show', compact('workshop'));
    }

    // ---------------------- EDIT ----------------------
    public function edit(Workshop $workshop)
    {
        $workshop->load('attendances');
        $users = User::all();
        return view('workshop.edit', compact('workshop', 'users'));
    }

    // ---------------------- UPDATE ----------------------

    public function update(Request $request, Workshop $workshop)
    {
        // return $request->all();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'created_by' => 'required|exists:users,id',
            'attendances' => 'nullable|array',
            'attendances.*.name' => 'required|string|max:255',
            'attendances.*.job_title' => 'nullable|string|max:255',
            'attendances.*.department' => 'nullable|string|max:255',
        ]);

        // Combine start date and time
        $startsAt = $validated['start_date'] . ' ' . $validated['start_time'];

        // Combine end date and time if provided
        $endsAt = null;
        if ($validated['end_date'] && $validated['end_time']) {
            $endsAt = $validated['end_date'] . ' ' . $validated['end_time'];
        }

        // Update workshop
        $workshop->update([
           'is_active' => $validated['is_active'] ?? false,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'location' => $validated['location'],
            'created_by' => $validated['created_by'],
        ]);

        // Handle attendances
        $this->updateAttendances($workshop, $validated['attendances'] ?? []);

        return redirect()->route('workshop.index')
            ->with('success', 'تم تحديث الورشة والحضور بنجاح.');
    }


    private function updateAttendances(Workshop $workshop, array $attendanceData)
    {
        // Delete all existing attendances and create new ones
        $workshop->attendances()->delete();

        foreach ($attendanceData as $attendance) {
            if (!empty($attendance['name'])) {
                WorkshopAttendance::create([
                    'workshop_id' => $workshop->id,
                    'attendee_name' => $attendance['name'],
                    'job_title' => $attendance['job_title'] ?? null,
                    'department' => $attendance['department'] ?? null,
                ]);
            }
        }
    }

    // ---------------------- DESTROY ----------------------
    public function destroy(Workshop $workshop)
    {

        $workshop->delete();

        return redirect()->route('workshop.index')
            ->with('success', 'تم حذف الورشة بنجاح');
    }
}
