<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Http\Request;

class TimelineController extends Controller
{

    public function index(Request $request)
    {
        $year = $request->get('year', 2026);

        // Handle Target User (Delegation Strategy)
        $targetUserId = $request->get('target_user');
        $currentUser = auth()->user();

        if ($targetUserId && $targetUserId != $currentUser->id) {
            // Check permissions
            if (!$this->canManageUser($targetUserId)) {
                abort(403, 'Unauthorized access to this timeline');
            }
            $displayedUser = User::findOrFail($targetUserId);
        } else {
            $displayedUser = $currentUser;
        }

        $events = CalendarEvent::where('year', $year)
            ->where('target_user_id', $displayedUser->id) // Strict filtering
            ->orderBy('start_date')
            ->get()
            ->map(fn($event) => [
                'id' => $event->id,
                'title' => $event->title,
                'start_date' => $event->start_date->toDateString(),
                'end_date' => $event->end_date->toDateString(),
                'bg_color' => $event->bg_color,
                'type' => $event->type,
            ]);

        return view('timeline.index', compact('events', 'year', 'displayedUser'));
    }

    protected function canManageUser(int $targetUserId): bool
    {
        return \App\Models\CalendarDelegation::where('manager_id', auth()->id())
            ->where('employee_id', $targetUserId)
            ->where('is_active', true)
            ->exists();
    }
    public function show(CalendarEvent $calendarEvent)
    {
        // Check if the event is currently active
        $today = now()->startOfDay();
        $status = $calendarEvent->status; // Using the accessor we built earlier

        // Calculate days remaining or days since completion
        $daysRemaining = $today->diffInDays($calendarEvent->start_date, false);

        return view('timeline.show', [
            'event' => $calendarEvent,
            'status' => $status,
            'daysRemaining' => $daysRemaining
        ]);
    }
}
