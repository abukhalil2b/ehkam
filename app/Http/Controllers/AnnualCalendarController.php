<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Models\CalendarDelegation;
use App\Models\OrgUnit;
use App\Models\AppointmentRequest;
use App\Models\CalendarPermission;
use App\Models\CalendarSlotProposal;
use App\Notifications\EventAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnnualCalendarController extends Controller
{
    /**
     * Display calendar with optimized loading
     */
    public function show(Request $request)
    {
        return view('calendar.show');
    }

    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        if (!is_numeric($year) || strlen($year) != 4) {
            $year = date('Y');
        }

        $targetUserId = $request->get('target_user');
        $currentUser = auth()->user();

        // 1. Identify which calendar to display
        if ($targetUserId && $targetUserId != $currentUser->id) {
            if (!$this->canManageUser($targetUserId)) {
                abort(403, 'Unauthorized access to this calendar');
            }
            $displayedUser = User::findOrFail($targetUserId);
        } else {
            $displayedUser = $currentUser;
        }

        $userId = $displayedUser->id;

        // Cache key based on user and year
        $cacheKey = "calendar_events_{$userId}_{$year}";

        // TEMPORARY: Force clear cache to fix the "wrong events" issue immediately for the user
        // We can remove this later or rely on the Refresh button.
        Cache::forget($cacheKey);

        $events = Cache::remember($cacheKey, 300, function () use ($year, $userId) {
            $calendarEvents = CalendarEvent::with(['user:id,name', 'targetUser:id,name'])
                ->where('target_user_id', $userId) // Strict: Only events ON this calendar
                ->inYear($year)
                ->get()
                ->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'startDate' => $event->start_date->format('Y-m-d H:i:s'),
                        'endDate' => $event->end_date->format('Y-m-d H:i:s'),
                        'type' => $event->type,
                        'program' => $event->program,
                        'bg_color' => $event->bg_color,
                        'notes' => $event->notes,
                        'duration' => $event->duration,
                        'duration_human' => $event->duration_human,
                        'status' => $event->status,
                        'status_color' => $event->status_color,
                        'creator' => $event->user->name ?? 'غير معروف',
                        'target' => $event->targetUser->name ?? 'غير معروف',
                        'hijriDate' => $event->hijri_date,
                        'startTime' => $event->start_date->format('H:i'),
                        'endTime' => $event->end_date->format('H:i'),
                        'is_public' => $event->is_public,
                        'target_user_id' => $event->target_user_id,
                        'can_edit' => $this->userCanEdit($event),
                        'event_type' => 'calendar_event',
                    ];
                });

            // Add appointment requests as calendar events
            $appointmentEvents = $this->getAppointmentEvents($userId, $year);

            return $calendarEvents->merge($appointmentEvents);
        });

        // 3. FETCH DELEGATORS (The "Shared with Me" users)
        // These are the people who have an active delegation where current user is the employee
        $sharedWithMe = User::whereHas('delegationsAsManager', function ($query) use ($currentUser) {
            $query->where('employee_id', $currentUser->id)->where('is_active', true);
        })->select('id', 'name')->get();

        $managedUsers = $this->getManagedUsers();

        // Fetch User's Active Departments for calendar switcher
        $myDepartments = $currentUser->positionHistory()
            ->whereNull('end_date')
            ->with('orgUnit')
            ->get()
            ->pluck('orgUnit')
            ->filter()
            ->unique('id');

        return view('calendar.index', compact('year', 'events', 'managedUsers', 'displayedUser', 'sharedWithMe', 'myDepartments'));
    }

    /**
     * Get appointment requests as calendar events
     */
    protected function getAppointmentEvents(int $userId, int $year): \Illuminate\Support\Collection
    {
        // Get appointments where user is the minister or requester, and has selected slots
        $appointments = AppointmentRequest::where(function ($q) use ($userId) {
            $q->where('minister_id', $userId)
                ->orWhere('requester_id', $userId);
        })
            ->where('status', 'booked')
            ->whereHas('slotProposals', function ($q) {
                $q->whereNotNull('selected_by');
            })
            ->with(['slotProposals' => function ($q) {
                $q->whereNotNull('selected_by');
            }, 'requester', 'minister'])
            ->get();

        return $appointments->flatMap(function ($appointment) use ($year) {
            return $appointment->slotProposals->map(function ($slot) use ($appointment, $year) {
                if ($slot->start_date->year != $year) {
                    return null;
                }

                return [
                    'id' => 'appointment_' . $appointment->id . '_slot_' . $slot->id,
                    'title' => 'موعد: ' . $appointment->subject,
                    'startDate' => $slot->start_date->format('Y-m-d H:i:s'),
                    'endDate' => $slot->end_date->format('Y-m-d H:i:s'),
                    'type' => 'appointment',
                    'program' => null,
                    'bg_color' => '#10b981', // Green for appointments
                    'notes' => $appointment->description . ($slot->location ? ' | المكان: ' . $slot->location : ''),
                    'duration' => $slot->start_date->diffInMinutes($slot->end_date),
                    'duration_human' => $slot->start_date->diffForHumans($slot->end_date, true),
                    'status' => 'upcoming',
                    'status_color' => '#10b981',
                    'creator' => $appointment->requester->name ?? 'غير معروف',
                    'target' => $appointment->minister->name ?? 'غير معروف',
                    'hijriDate' => \App\Helpers\HijriDateHelper::format($slot->start_date),
                    'startTime' => $slot->start_date->format('H:i'),
                    'endTime' => $slot->end_date->format('H:i'),
                    'is_public' => false,
                    'target_user_id' => $appointment->minister_id,
                    'can_edit' => false,
                    'event_type' => 'appointment',
                    'appointment_id' => $appointment->id,
                ];
            })->filter();
        });
    }

    /**
     * Display department calendar (Shared View)
     */
    public function department(Request $request, OrgUnit $orgUnit)
    {
        // Permission check: User must be part of the unit or have permission
        // For now, assuming if they have the link and are auth, it's okay (or check if currentUnit match)
        // $this->authorize('view', $orgUnit); 

        $year = $request->get('year', date('Y'));

        // 1. Get all employees currently assigned to this unit
        // We use the `employeeAssignments` relation on OrgUnit model (verified in step 46)
        // Adjust based on your DB: usually assignments have user_id
        $userIds = $orgUnit->employeeAssignments()
            ->whereNull('end_date') // Active only
            ->pluck('user_id');

        // 2. Fetch events for ALL these users
        $calendarEvents = CalendarEvent::with(['user:id,name', 'targetUser:id,name'])
            ->whereIn('target_user_id', $userIds)
            ->inYear($year)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title . ' (' . ($event->targetUser->name ?? '?') . ')', // Append Name
                    'startDate' => $event->start_date->format('Y-m-d H:i:s'),
                    'endDate' => $event->end_date->format('Y-m-d H:i:s'),
                    'type' => $event->type,
                    'program' => $event->program,
                    'bg_color' => $event->bg_color,
                    'notes' => $event->notes,
                    'duration' => $event->duration,
                    'duration_human' => $event->duration_human,
                    'status' => $event->status,
                    'status_color' => $event->status_color,
                    'creator' => $event->user->name ?? 'غير معروف',
                    'target' => $event->targetUser->name ?? 'غير معروف',
                    'hijriDate' => $event->hijri_date,
                    'startTime' => $event->start_date->format('H:i'),
                    'endTime' => $event->end_date->format('H:i'),
                    'is_public' => $event->is_public,
                    'target_user_id' => $event->target_user_id,
                    'can_edit' => $this->userCanEdit($event),
                    'event_type' => 'calendar_event',
                ];
            });

        // Add appointment events for all users in the department
        $appointmentEvents = collect();
        foreach ($userIds as $uid) {
            $appointmentEvents = $appointmentEvents->merge($this->getAppointmentEvents($uid, $year));
        }

        $events = $calendarEvents->merge($appointmentEvents);

        $managedUsers = $this->getManagedUsers();
        $isDepartmentView = true;
        $departmentName = $orgUnit->name;

        return view('calendar.index', compact('year', 'events', 'managedUsers', 'isDepartmentView', 'departmentName'));
    }

    /**
     * Lazy load events via AJAX for better performance
     */
    public function loadEvents(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
        ]);

        $year = $request->year;
        $month = $request->month;

        $query = CalendarEvent::with(['user:id,name', 'targetUser:id,name'])
            ->forUser(auth()->id())
            ->inYear($year);

        if ($month) {
            $query->whereMonth('start_date', $month);
        }

        $events = $query->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'startDate' => $event->start_date->toIso8601String(),
                'endDate' => $event->end_date->toIso8601String(),
                'bg_color' => $event->bg_color,
                'status' => $event->status,
                'can_edit' => $this->userCanEdit($event),
            ];
        });

        return response()->json($events);
    }

    //create calendar for myself,my delgation, my Department

    public function create(Request $request)
    {
        $managedUsers = $this->getManagedUsers();

        // FIXED: Uncomment permission check to prevent unauthorized posting
        $allowedOrgUnits = OrgUnit::whereIn('type', ['Directorate', 'Department']) // Added Department as requested
            ->whereHas('calendarPermissions', function ($q) {
                $q->where('user_id', auth()->id())
                    ->whereIn('role', ['editor', 'admin']); // Ensure they have write access
            })
            ->select('id', 'unit_code', 'name', 'type')
            ->get();

        $year = $request->get('year', date('Y'));
        $preSelectedUser = $request->get('target_user');
        $preSelectedOrg = $request->get('target_org');

        return view('calendar.create', compact('year', 'managedUsers', 'allowedOrgUnits', 'preSelectedUser', 'preSelectedOrg'));
    }

    public function edit(CalendarEvent $calendarEvent)
    {
        if (!$this->userCanEdit($calendarEvent)) {
            abort(403, 'غير مصرح لك بتعديل هذا الحدث');
        }

        $managedUsers = $this->getManagedUsers();
        $year = $calendarEvent->year;

        return view('calendar.create', [
            'event' => $calendarEvent,
            'managedUsers' => $managedUsers,
            'year' => $year,
        ]);
    }

    /**
     * Unified validation with enhanced conflict detection
     * 
     * @param Request $request
     * @param CalendarEvent|null $existingEvent Pass the existing event when updating
     */
    protected function validateAndMerge(Request $request, ?CalendarEvent $existingEvent = null)
    {
        $today = now()->format('Y-m-d');
        $isUpdate = $existingEvent !== null;

        // Base validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:program,meeting,conference,competition,other',
            'program' => 'nullable|string|max:100',

            // Backend Validation: Min Date (Today)
            'start_date_day' => 'required|date|after_or_equal:' . $today,
            'start_date_time' => 'required|date_format:H:i',

            'end_date_day' => 'required|date|after_or_equal:start_date_day',
            'end_date_time' => 'required|date_format:H:i',

            'bg_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'notes' => 'nullable|string|max:1000',
            'is_public' => 'nullable|boolean',
        ];

        // For new events, target_type is required
        // For updates, it's optional (we keep the existing target)
        if ($isUpdate) {
            $rules['target_type'] = 'nullable|in:user,org';
            $rules['target_user_id'] = 'nullable|exists:users,id';
            $rules['target_org_id'] = 'nullable|exists:org_units,id';
        } else {
            $rules['target_type'] = 'required|in:user,org';
            $rules['target_user_id'] = 'nullable|required_if:target_type,user|exists:users,id';
            $rules['target_org_id'] = 'nullable|required_if:target_type,org|exists:org_units,id';
        }

        $validatedData = $request->validate($rules, [
            'start_date_day.after_or_equal' => 'تاريخ البداية لا يمكن أن يكون في الماضي.',
            'target_type.required' => 'يجب تحديد لمن هذا النشاط.',
        ]);

        $start = Carbon::parse($validatedData['start_date_day'] . ' ' . $validatedData['start_date_time']);
        $end = Carbon::parse($validatedData['end_date_day'] . ' ' . $validatedData['end_date_time']);

        // 1. End Date > Start Date
        if ($end->lte($start)) {
            throw ValidationException::withMessages([
                'end_date_time' => 'وقت النهاية يجب أن يكون بعد وقت البداية.',
            ]);
        }

        // Determine target (use existing for updates if not provided)
        $targetId = null;
        $targetOrgId = null;

        // Check if target_type was provided
        $targetType = $validatedData['target_type'] ?? null;

        if ($isUpdate && !$targetType) {
            // Keep existing target for updates
            $targetId = $existingEvent->target_user_id;
            $targetOrgId = $existingEvent->org_unit_id;
        } elseif ($targetType === 'user') {
            $targetId = $validatedData['target_user_id'] ?? ($isUpdate ? $existingEvent->target_user_id : null);
            if ($targetId && $targetId != auth()->id() && !$this->canManageUser($targetId)) {
                throw ValidationException::withMessages(['target_user_id' => 'غير مصرح لك بإدارة تقويم هذا المستخدم.']);
            }
        } elseif ($targetType === 'org') {
            $targetOrgId = $validatedData['target_org_id'] ?? ($isUpdate ? $existingEvent->org_unit_id : null);
            
            if ($targetOrgId) {
                $hasPermission = CalendarPermission::where('user_id', auth()->id())
                    ->where('org_unit_id', $targetOrgId)
                    ->exists();

                if (!$hasPermission) {
                    throw ValidationException::withMessages(['target_org_id' => 'ليس لديك صلاحية إضافة أنشطة لهذه الوحدة التنظيمية.']);
                }
            }
        }

        return [
            'title' => $validatedData['title'],
            'type' => $validatedData['type'],
            'program' => $validatedData['program'] ?? null,
            'start_date' => $start,
            'end_date' => $end,
            'bg_color' => $validatedData['bg_color'],
            'notes' => $validatedData['notes'] ?? null,
            'is_public' => $request->boolean('is_public'),
            'target_user_id' => $targetId,
            'org_unit_id' => $targetOrgId,
        ];
    }

    public function store(Request $request)
    {
        $data = $this->validateAndMerge($request);
        $data['user_id'] = auth()->id();

        DB::beginTransaction();
        try {
            // Conflict Logic (Updated to handle User OR OrgUnit)
            $conflicts = $this->getConflicts(
                $data['target_user_id'],
                $data['org_unit_id'],
                $data['start_date'],
                $data['end_date']
            );

            if ($conflicts->isNotEmpty() && !$request->boolean('force_save')) {
                DB::rollBack();
                return $this->handleConflict($conflicts, $data); // Pass raw data to refill form
            }

            CalendarEvent::create($data);

            DB::commit();

            return redirect()
                ->route('calendar.index', ['year' => $data['start_date']->year])
                ->with('success', 'تم إضافة الحدث بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        if (!$this->userCanEdit($calendarEvent)) {
            abort(403, 'غير مصرح لك بتعديل هذا الحدث');
        }

        // Pass existing event to validation so target_type is optional
        $data = $this->validateAndMerge($request, $calendarEvent);

        DB::beginTransaction();
        try {
            // Check for conflicts, excluding the current event being updated
            $conflicts = $this->getConflicts(
                $data['target_user_id'],
                $data['org_unit_id'],
                $data['start_date'],
                $data['end_date'],
                $calendarEvent->id  // Exclude this event from conflict check
            );

            if ($conflicts->isNotEmpty() && !$request->boolean('force_save')) {
                DB::rollBack();
                return $this->handleConflict($conflicts, $data, $calendarEvent);
            }

            $calendarEvent->update($data);

            // Clear cache
            $originalYear = $calendarEvent->original['start_date']->year ?? $calendarEvent->year;
            $newYear = $calendarEvent->year; // or calculated from request

            // Clear Old Year Cache
            Cache::forget("calendar_events_{$calendarEvent->target_user_id}_{$originalYear}");

            // If the year changed, Clear New Year Cache too
            if ($originalYear !== $newYear) {
                Cache::forget("calendar_events_{$calendarEvent->target_user_id}_{$newYear}");
            }

            DB::commit();

            return redirect()
                ->route('calendar.index', ['year' => $calendarEvent->year])
                ->with('success', 'تم تحديث الحدث بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    /**
     * Get conflicting events with details
     */
    protected function getConflicts($targetUserId, $orgUnitId, $startDate, $endDate, $ignoreEventId = null)
    {
        return CalendarEvent::query()
            ->where(function ($q) use ($targetUserId, $orgUnitId) {
                if ($targetUserId) {
                    $q->where('target_user_id', $targetUserId);
                } elseif ($orgUnitId) {
                    $q->where('org_unit_id', $orgUnitId);
                }
            })
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '>=', $startDate)->where('start_date', '<', $endDate);
                })
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('end_date', '>', $startDate)->where('end_date', '<=', $endDate);
                    })
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<', $startDate)->where('end_date', '>', $endDate);
                    });
            })
            ->when($ignoreEventId, fn($q) => $q->where('id', '!=', $ignoreEventId))
            ->with('user')
            ->get();
    }

    /**
     * Handle conflict with detailed response
     */
    protected function handleConflict($conflicts, $data, $event = null)
    {
        $conflictDetails = $conflicts->map(function ($conflict) {
            return [
                'id' => $conflict->id,
                'title' => $conflict->title,
                'start_date' => $conflict->start_date->format('Y-m-d H:i'),
                'end_date' => $conflict->end_date->format('Y-m-d H:i'),
                'creator' => $conflict->user->name,
            ];
        });

        // Suggest next available slot
        $tempEvent = new CalendarEvent([
            'target_user_id' => $data['target_user_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);
        $suggestedSlot = $tempEvent->suggestNextSlot();

        return back()
            ->withInput()
            ->with('conflicts', $conflictDetails)
            ->with('suggested_slot', $suggestedSlot?->format('Y-m-d H:i'))
            ->withErrors(['conflict' => 'يوجد تعارض في الوقت مع الأنشطة التالية']);
    }

    /**
     * Move event via drag & drop (API)
     */
    public function moveEvent(Request $request, CalendarEvent $calendarEvent)
    {
        if (!$this->userCanEdit($calendarEvent)) {
            return response()->json([
                'message' => 'غير مصرح لك بتحريك هذا الحدث'
            ], 403);
        }

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $newStart = Carbon::parse($request->start_date);
        $newEnd = Carbon::parse($request->end_date);

        DB::beginTransaction();
        try {
            $conflicts = $this->getConflicts(
                $calendarEvent->target_user_id,
                $newStart,
                $newEnd,
                $calendarEvent->id
            );

            if ($conflicts->isNotEmpty()) {
                DB::rollBack();
                return response()->json([
                    'message' => 'يوجد تعارض في الوقت',
                    'conflicts' => $conflicts->map(fn($c) => [
                        'title' => $c->title,
                        'start_date' => $c->start_date->format('Y-m-d H:i'),
                    ]),
                ], 422);
            }

            $calendarEvent->update([
                'start_date' => $newStart,
                'end_date' => $newEnd,
            ]);

            $originalYear = $calendarEvent->original['start_date']->year ?? $calendarEvent->year;
            $newYear = $calendarEvent->year; // or calculated from request

            // Clear Old Year Cache
            Cache::forget("calendar_events_{$calendarEvent->target_user_id}_{$originalYear}");

            // If the year changed, Clear New Year Cache too
            if ($originalYear !== $newYear) {
                Cache::forget("calendar_events_{$calendarEvent->target_user_id}_{$newYear}");
            }

            DB::commit();

            return response()->json([
                'message' => 'تم نقل الحدث بنجاح',
                'event' => [
                    'id' => $calendarEvent->id,
                    'startDate' => $calendarEvent->start_date->toIso8601String(),
                    'endDate' => $calendarEvent->end_date->toIso8601String(),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء نقل الحدث'], 500);
        }
    }

    // Remove "CalendarEvent $calendarEvent" from the signature to bypass automatic 404
    public function destroy($id)
    {
        // Try to find it manually
        $calendarEvent = CalendarEvent::find($id);

        // If it doesn't exist (already deleted), just clear cache and return success
        if (!$calendarEvent) {
            // We need to guess the cache keys to clear since we don't have the event object.
            // Or simply return back with a message.
            return back()->with('info', 'هذا الحدث محذوف بالفعل');
        }

        if (!$this->userCanEdit($calendarEvent)) {
            abort(403, 'غير مصرح لك بحذف هذا الحدث');
        }

        $year = $calendarEvent->year;
        $targetUserId = $calendarEvent->target_user_id;

        $calendarEvent->delete();

        // Clear Cache
        Cache::forget("calendar_events_{$targetUserId}_{$year}");

        return back()->with('success', 'تم حذف الحدث بنجاح');
    }

    public function settings()
    {
        $userActivity = User::withCount(['calendarEvents as events_count'])
            ->having('events_count', '>', 0)
            ->orderBy('events_count', 'desc')
            ->get();

        $stats = [
            'total_events' => CalendarEvent::count(),
            'active_users' => $userActivity->count(),
            'upcoming_this_month' => CalendarEvent::whereMonth('start_date', now()->month)
                ->whereYear('start_date', now()->year)
                ->count(),
            'active_now' => CalendarEvent::active()->count(),
        ];

        $delegations = CalendarDelegation::with(['manager', 'employee'])
            ->where('manager_id', auth()->id())
            ->orWhere('employee_id', auth()->id())
            ->get();

        return view('calendar.settings', compact('userActivity', 'stats', 'delegations'));
    }

    public function calendarPermissionIndex()
    {
        return view('calendar.permissions.index');
    }

    // ========== PERMISSION HELPERS ==========

    protected function userCanEdit(CalendarEvent $event): bool
    {
        $userId = auth()->id();

        return $event->user_id === $userId
            || $event->target_user_id === $userId
            || $this->canManageUser($event->target_user_id);
    }

    protected function canManageUser(int $targetUserId): bool
    {
        return CalendarDelegation::where('manager_id', auth()->id())
            ->where('employee_id', $targetUserId)
            ->where('is_active', true)
            ->exists();
    }

    protected function getManagedUsers()
    {
        $directManaged = CalendarDelegation::where('manager_id', auth()->id())
            ->where('is_active', true)
            ->pluck('employee_id')
            ->toArray();

        return User::whereIn('id', $directManaged)
            ->select('id', 'name')
            ->get();
    }

    public function refreshCache()
    {
        $userId = auth()->id();
        $year = request('year', date('Y'));
        Cache::forget("calendar_events_{$userId}_{$year}");

        return back()->with('success', 'تم تحديث البيانات بنجاح');
    }

    public function deleteAllNotifications()
    {
        auth()->user()->notifications()->delete();
        return back()->with('success', 'تم حذف جميع التنبيهات');
    }
}
