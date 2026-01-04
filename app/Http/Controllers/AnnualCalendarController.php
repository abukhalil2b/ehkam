<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class AnnualCalendarController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $events = CalendarEvent::where('year', $year)
            ->orderBy('start_date')
            ->get()
            ->map(function ($event) {
                return [
                    'id'        => $event->id,
                    'title'     => $event->title,
                    'startDate' => $event->start_date->format('Y-m-d'), // JS compatible
                    'endDate'   => $event->end_date->format('Y-m-d'),   // JS compatible
                    'type'      => $event->type,
                    'program'   => $event->program,
                    'bg_color'  => $event->bg_color,
                    'notes'     => $event->notes,
                    'duration'  => $event->duration, // Calculated in Model
                    'status'    => $event->status,   // Calculated in Model
                    // Map status to specific Tailwind/Hex colors for the legend
                    'status_color' => match ($event->status) {
                        'active' => '#059669',    // Emerald 600
                        'upcoming' => '#4b5563',  // Gray 600
                        'completed' => '#2563eb', // Blue 600
                        default => '#2563eb'
                    }
                ];
            });

        return view('calendar.index', compact('year', 'events'));
    }

    public function create(Request $request)
    {
        $year = $request->get('year', date('Y'));
        return view('calendar.create', compact('year'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateEvent($request);

        CalendarEvent::create($validated);

        return redirect()->route('calendar.index', ['year' => date('Y', strtotime($validated['start_date']))])
            ->with('success', 'تم إضافة الحدث بنجاح');
    }


    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $validated = $this->validateEvent($request);

        $calendarEvent->update($validated);

        return redirect()->route('calendar.index', ['year' => $calendarEvent->year])
            ->with('success', 'تم تحديث الحدث بنجاح');
    }

    protected function validateEvent(Request $request)
    {
        return $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|string|max:50',
            'program'    => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'bg_color'   => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'notes'      => 'nullable|string',
        ]);
    }
}
