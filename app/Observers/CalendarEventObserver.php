<?php

namespace App\Observers;

use App\Models\CalendarEvent;
use App\Models\CalendarAuditLog;
use Illuminate\Support\Facades\Auth;

class CalendarEventObserver
{
    /**
     * Handle the CalendarEvent "created" event.
     */
    public function created(CalendarEvent $calendarEvent): void
    {
        $this->logAction($calendarEvent, 'created', null, $calendarEvent->toArray());
    }

    /**
     * Handle the CalendarEvent "updated" event.
     */
    public function updated(CalendarEvent $calendarEvent): void
    {
        // Detect if it was a "move" action (drag & drop) vs normal update
        $isMove = $calendarEvent->wasChanged('start_date') || $calendarEvent->wasChanged('end_date');
        $action = $isMove && count($calendarEvent->getDirty()) <= 3 ? 'moved' : 'updated';

        $oldData = [];
        $newData = [];

        foreach ($calendarEvent->getDirty() as $key => $value) {
            $oldData[$key] = $calendarEvent->getOriginal($key);
            $newData[$key] = $value;
        }

        $this->logAction($calendarEvent, $action, $oldData, $newData);
    }

    /**
     * Handle the CalendarEvent "deleted" event.
     */
    public function deleted(CalendarEvent $calendarEvent): void
    {
        $this->logAction($calendarEvent, 'deleted', $calendarEvent->toArray(), null);
    }

    /**
     * Helper to insert into logs
     */
    protected function logAction(CalendarEvent $event, string $action, ?array $oldData, ?array $newData): void
    {
        if (!Auth::check()) {
            return; // Don't log system/seeder actions if no user context
        }

        CalendarAuditLog::create([
            'calendar_event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => $newData ? json_encode($newData) : null,
            'created_at' => now(),
        ]);
    }
}
