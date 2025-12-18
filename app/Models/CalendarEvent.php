<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'type',
        'program',
        'notes',
        'bg_color',
        'year',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /* Computed Attributes */

    public function getDurationAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getStatusAttribute(): string
    {
        $today = now()->startOfDay();

        if ($today->lt($this->start_date)) {
            return 'upcoming';
        }

        if ($today->gt($this->end_date)) {
            return 'completed';
        }

        return 'active';
    }
}
