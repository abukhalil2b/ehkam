<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CalendarEvent extends Model
{
    protected $fillable = ['title', 'start_date', 'end_date', 'type', 'program', 'notes', 'bg_color', 'year'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /**
     * Set the year automatically when start_date is updated.
     */
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($event) {
            if ($event->start_date) {
                $event->year = $event->start_date->year;
            }
        });
    }

    /* Computed Attributes (Accessors) */

    public function getDurationAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getStatusAttribute(): string
    {
        $today = now()->startOfDay();
        if ($today->lt($this->start_date)) return 'upcoming';
        if ($today->gt($this->end_date)) return 'completed';
        return 'active';
    }

}

