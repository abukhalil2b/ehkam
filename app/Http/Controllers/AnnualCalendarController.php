<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AnnualCalendarController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $events = CalendarEvent::where('year', $year)
            ->orderBy('start_date')
            ->get()
            ->map(fn($event) => [
                'id'        => $event->id,
                'title'     => $event->title,
                'startDate' => $event->start_date->toDateString(),
                'endDate'   => $event->end_date->toDateString(),
                'type'      => $event->type,
                'program'   => $event->program,
                'bg_color'  => $event->bg_color ?? '#2563eb',
                'duration'  => $event->duration,
                'status'    => $event->status,
            ]);

        return view('calendar.index', compact('year', 'events'));
    }

    public function create(Request $request)
    {
        $year = $request->get('year', date('Y'));

        return view('calendar.create', compact('year'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|string|max:50',
            'program'    => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'bg_color'   => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'notes'      => 'nullable|string',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end   = Carbon::parse($validated['end_date']);
        $today = Carbon::today();

        // Duration (inclusive)
        $duration = $start->diffInDays($end) + 1;

        // Status
        if ($today->between($start, $end)) {
            $status = 'active';
        } elseif ($today->lt($start)) {
            $status = 'upcoming';
        } else {
            $status = 'completed';
        }

        CalendarEvent::create([
            'title'      => $validated['title'],
            'type'       => $validated['type'],
            'program'    => $validated['program'],
            'start_date' => $start,
            'end_date'   => $end,
            'year'       => $start->year,
            'duration'   => $duration,
            'status'     => $status,
            'bg_color'   => $validated['bg_color'],
            'notes'      => $validated['notes'],
        ]);

        return redirect()
            ->route('calendar.index', ['year' => $start->year])
            ->with('success', 'تم إضافة الحدث بنجاح');
    }

    public function edit(CalendarEvent $calendarEvent)
    {
        return view('calendar.edit', [
            'event' => $calendarEvent
        ]);
    }


    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|string|max:50',
            'program'    => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'bg_color'   => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'notes'      => 'nullable|string',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end   = Carbon::parse($validated['end_date']);
        $today = Carbon::today();

        // Duration (inclusive)
        $duration = $start->diffInDays($end) + 1;

        // Status
        if ($today->between($start, $end)) {
            $status = 'active';
        } elseif ($today->lt($start)) {
            $status = 'upcoming';
        } else {
            $status = 'completed';
        }

        $calendarEvent->update([
            'title'      => $validated['title'],
            'type'       => $validated['type'],
            'program'    => $validated['program'],
            'start_date' => $start,
            'end_date'   => $end,
            'year'       => $start->year,
            'duration'   => $duration,
            'status'     => $status,
            'bg_color'   => $validated['bg_color'],
            'notes'      => $validated['notes'],
        ]);

        return redirect()
            ->route('calendar.index', ['year' => $calendarEvent->year])
            ->with('success', 'تم تحديث الحدث بنجاح');
    }
}
