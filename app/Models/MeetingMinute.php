<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MeetingMinute extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
        'date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($meetingMinute) {
            $meetingMinute->public_token = Str::random(32);
        });
    }


    public function writtenBy()
    {
        return $this->belongsTo(User::class, 'written_by');
    }

    public function attendances()
    {
        return $this->hasMany(MeetingAttendance::class);
    }
}
