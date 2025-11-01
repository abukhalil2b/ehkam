<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use App\Models\WorkshopAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WorkshopController extends Controller
{

    public function attendance_register(Request $request)
    {
        // 1. Find the currently active workshop
        // We assume only one workshop should be active for attendance at a time.
        $workshop = Workshop::where('active', true)->first();

        // Handle case where no active workshop is found
        if (!$workshop) {
            $message = 'لا توجد ورشة عمل نشطة حاليًا لتسجيل الحضور.';

            if ($request->isMethod('POST')) {
                return back()->with('error', $message);
            }

            return view('workshow_attendance_register', [
                'workshop' => null,
                'attendances' => collect([]), // Pass an empty collection
            ])->with('warning', $message);
        }

        // 2. Handle POST Request (Registration Submission)
        if ($request->isMethod('POST')) {
            // Validate the incoming data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'job_title' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
            ], [
                // Custom Arabic validation messages
                'name.required' => 'حقل الاسم مطلوب.',
                'name.string' => 'الاسم يجب أن يكون نصًا.',
                'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرفًا.',
            ]);

            // Check for duplicate registration based on name and workshop_id
            $existingAttendance = WorkshopAttendance::where('workshop_id', $workshop->id)
                ->where('name', $validated['name'])
                ->first();

            if ($existingAttendance) {
                return back()
                    ->withInput()
                    ->with('warning', 'لقد تم تسجيل حضورك مسبقًا في ورشة العمل هذه.');
            }

            // Create the attendance record
            WorkshopAttendance::create([
                'workshop_id' => $workshop->id,
                'name' => $validated['name'],
                'job_title' => $validated['job_title'],
                'department' => $validated['department'],
            ]);

            // Redirect back with a success message
            return back()->with('success', 'تم تسجيل حضورك بنجاح! نرحب بك.');
        }

        // 3. Handle GET Request (Display form and list)
        $attendances = WorkshopAttendance::where('workshop_id', $workshop->id)
            ->latest() // Order by creation date, newest first
            ->get();

        return view('workshow_attendance_register', compact('attendances', 'workshop'));
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
        // 1. Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date', // Validation for the date field
            'attendances' => 'nullable|array',
            'attendances.*' => 'nullable|string|max:255',
        ], [
            // Custom Arabic validation messages for better user experience
            'title.required' => 'حقل العنوان مطلوب.',
            'date.required' => 'حقل التاريخ مطلوب.',
            'date.date' => 'التاريخ المدخل غير صحيح.',
            'attendances.*.string' => 'يجب أن يكون اسم الحاضر نصًا.',
            'attendances.*.max' => 'الاسم لا يمكن أن يتجاوز 255 حرفًا.'
        ]);


        // 2. Create the main Workshop record
        $workshop = Workshop::create([
            'title' => $validated['title'],
            'date' => $validated['date'], // Storing the selected date
            'written_by' => auth()->id(),
            // Assuming 'place' and 'active' are handled by defaults in the model or database
        ]);

        // 3. Prepare and insert attendance records
        if (!empty($validated['attendances'])) {
            $attendanceRecords = [];
            $now = Carbon::now();

            foreach ($validated['attendances'] as $name) {
                $trimmedName = trim($name);
                if ($trimmedName !== '') {
                    // Collect attendance data for batch insertion
                    $attendanceRecords[] = [
                        'workshop_id' => $workshop->id,
                        'name' => htmlspecialchars($trimmedName), // Sanitize input name
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // Use insert to batch create attendance records for performance
            if (!empty($attendanceRecords)) {
                WorkshopAttendance::insert($attendanceRecords);
            }
        }

        // 4. Redirect with success message
        return redirect()->route('workshop.index')
            ->with('success', 'تم إنشاء محضر الورشة بنجاح.');
    }

    // ---------------------- SHOW ----------------------
    public function show(Workshop $workshop)
    {
        $workshop->load(['writtenBy', 'attendances']);

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
        // 1. Validation: Includes all fields sent from the edit form.
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'place' => 'nullable|string|max:255', 
            'written_by' => 'required|exists:users,id', 
            'attendances' => 'nullable|array',
            'attendances.*' => 'nullable|string|max:255',
        ], [
            // Custom Arabic validation messages
            'title.required' => 'حقل العنوان مطلوب.',
            'date.required' => 'حقل التاريخ مطلوب.',
            'written_by.required' => 'يجب اختيار كاتب المحضر.',
            'written_by.exists' => 'المستخدم المحدد غير موجود.',
            'place.string' => 'المكان يجب أن يكون نصًا.',
            'attendances.*.string' => 'يجب أن يكون اسم الحاضر نصًا.',
        ]);

        // 2. Update the main Workshop record
        $workshop->update([
            'title' => $validated['title'],
            'place' => $validated['place'] ?? null,
            'written_by' => $validated['written_by'],
        ]);

        // 3. Refresh attendances (Delete old and insert new)
        $workshop->attendances()->delete();

        $attendanceRecords = [];
        if (!empty($validated['attendances'])) {
            $now = Carbon::now();
            foreach ($validated['attendances'] as $name) {
                $trimmedName = trim($name);
                if ($trimmedName !== '') {
                    $attendanceRecords[] = [
                        'workshop_id' => $workshop->id,
                        'name' => htmlspecialchars($trimmedName),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // Batch insert for performance
            if (!empty($attendanceRecords)) {
                WorkshopAttendance::insert($attendanceRecords);
            }
        }

        // 4. Redirect
        return redirect()->route('workshop.index')
            ->with('success', 'تم تحديث محضر الورشة بنجاح.');
    }

    // ---------------------- DESTROY ----------------------
    public function destroy(Workshop $workshop)
    {

        $workshop->delete();

        return redirect()->route('workshop.index')
            ->with('success', 'تم حذف الورشة بنجاح');
    }
}
