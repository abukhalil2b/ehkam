<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingAttendance extends Model
{
    protected $guarded = [];
    protected $casts = [
        'signed_at' => 'datetime',
    ];


    public function meetingMinute()
    {
        return $this->belongsTo(MeetingMinute::class);
    }
}
