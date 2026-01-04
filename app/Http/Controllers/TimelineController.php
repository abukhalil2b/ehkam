<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class TimelineController extends Controller
{

    public function index(Request $request)
    {
        $year = $request->get('year', 2026);

        $events = CalendarEvent::where('year', $year)
            ->orderBy('start_date')
            ->get()
            ->map(fn($event) => [
                'id'         => $event->id,
                'title'      => $event->title,
                'start_date' => $event->start_date->toDateString(),
                'end_date'   => $event->end_date->toDateString(),
                'bg_color'   => $event->bg_color, // Use the hex color from DB
                'type'       => $event->type,
            ]);

        return view('timeline.index', compact('events', 'year'));
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
