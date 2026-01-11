<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Models\CalendarDelegation;
use App\Models\OrgUnit;
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

        // Handle Target User (Delegation)
        $targetUserId = $request->get('target_user');
        $currentUser = auth()->user();

        if ($targetUserId && $targetUserId != $currentUser->id) {
            // Check if current user has permission to view target user's calendar
            // This is a basic check. You might want to use policies.
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
            return CalendarEvent::with(['user:id,name', 'targetUser:id,name'])
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
                    ];
                });
        });

        $managedUsers = $this->getManagedUsers();

        return view('calendar.index', compact('year', 'events', 'managedUsers', 'displayedUser'));
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
        $events = CalendarEvent::with(['user:id,name', 'targetUser:id,name'])
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
                ];
            });

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

    public function create(Request $request)
    {
        $managedUsers = $this->getManagedUsers();
        $year = $request->get('year', date('Y'));
        $preSelectedUser = $request->get('target_user'); // ID of user we were viewing

        return view('calendar.create', compact('year', 'managedUsers', 'preSelectedUser'));
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
     */
    protected function validateAndMerge(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:program,meeting,conference,competition',
            'program' => 'nullable|string|max:100',
            'start_date_day' => 'required|date',
            'start_date_time' => 'required|date_format:H:i',
            'end_date_day' => 'required|date|after_or_equal:start_date_day',
            'end_date_time' => 'required|date_format:H:i',
            'bg_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'notes' => 'nullable|string|max:1000',
            'target_user_id' => 'nullable|exists:users,id',
            'is_public' => 'nullable|boolean',
        ]);

        $start = Carbon::parse($validatedData['start_date_day'] . ' ' . $validatedData['start_date_time']);
        $end = Carbon::parse($validatedData['end_date_day'] . ' ' . $validatedData['end_date_time']);

        // 1. End Date must be after Start Date
        if ($end->lte($start)) {
            throw ValidationException::withMessages([
                'end_date_time' => 'وقت النهاية يجب أن يكون بعد وقت البداية.',
            ]);
        }

        // 2. NEW: Prevent Zero/Short Duration (Logic from your snippet)
        if ($start->diffInMinutes($end) < 15) {
            throw ValidationException::withMessages([
                'end_date_time' => 'يجب أن تكون مدة النشاط 15 دقيقة على الأقل',
            ]);
        }

        $targetUserId = $validatedData['target_user_id'] ?? auth()->id();

        // Check permissions
        if ($targetUserId !== auth()->id() && !$this->canManageUser($targetUserId)) {
            throw ValidationException::withMessages([
                'target_user_id' => 'غير مصرح لك بإدارة تقويم هذا المستخدم.',
            ]);
        }

        return [
            'title' => $validatedData['title'],
            'type' => $validatedData['type'],

            // FIX: Use null coalescing operator (?? null) for optional fields
            'program' => $validatedData['program'] ?? null,

            'start_date' => $start,
            'end_date' => $end,
            'bg_color' => $validatedData['bg_color'],

            // FIX: Use null coalescing operator (?? null) for notes
            'notes' => $validatedData['notes'] ?? null,

            'is_public' => $request->boolean('is_public'),
            'target_user_id' => $targetUserId,
        ];
    }

    public function store(Request $request)
    {
        $data = $this->validateAndMerge($request);
        $data['user_id'] = auth()->id();

        DB::beginTransaction();
        try {
            // Check for conflicts
            $conflicts = $this->getConflicts(
                $data['target_user_id'],
                $data['start_date'],
                $data['end_date']
            );

            if ($conflicts->isNotEmpty() && !$request->boolean('force_save')) {
                DB::rollBack();
                return $this->handleConflict($conflicts, $data);
            }

            $event = CalendarEvent::create($data);

            // Send notification if assigning to another user
            if ($data['target_user_id'] !== auth()->id()) {
                $targetUser = User::find($data['target_user_id']);
                $targetUser->notify(new EventAssignedNotification($event));
            }

            // Clear cache
            Cache::forget("calendar_events_{$data['target_user_id']}_{$data['start_date']->year}");

            DB::commit();

            // Redirect with target_user if applicable
            $redirectParams = ['year' => $data['start_date']->year];
            if ($data['target_user_id'] !== auth()->id()) {
                $redirectParams['target_user'] = $data['target_user_id'];
            }

            return redirect()
                ->route('calendar.index', $redirectParams)
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

        $data = $this->validateAndMerge($request);

        DB::beginTransaction();
        try {
            $conflicts = $this->getConflicts(
                $data['target_user_id'],
                $data['start_date'],
                $data['end_date'],
                $calendarEvent->id
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
    protected function getConflicts($targetUserId, $startDate, $endDate, $ignoreEventId = null)
    {
        return CalendarEvent::with('user')
            ->where('target_user_id', $targetUserId)
            ->where(function ($query) use ($startDate, $endDate) {
                // Your robust overlap logic
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Case 1: New event starts inside an existing event
                    $q->where('start_date', '>=', $startDate)
                        ->where('start_date', '<', $endDate);
                })
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                    // Case 2: New event ends inside an existing event
                    $q->where('end_date', '>', $startDate)
                        ->where('end_date', '<=', $endDate);
                })
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                    // Case 3: New event completely covers/envelops an existing event
                    $q->where('start_date', '<', $startDate)
                        ->where('end_date', '>', $endDate);
                });
            })
            ->when($ignoreEventId, function ($q) use ($ignoreEventId) {
                // Important: Exclude the event itself when updating
                $q->where('id', '!=', $ignoreEventId);
            })
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
