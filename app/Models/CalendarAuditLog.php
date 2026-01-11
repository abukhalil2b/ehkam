<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarAuditLog extends Model
{
    public $timestamps = false; 

    protected $fillable = [
        'calendar_event_id',
        'user_id',
        'action',
        'old_data',
        'new_data',
        'created_at',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(CalendarEvent::class, 'calendar_event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}