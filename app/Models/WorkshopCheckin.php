<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopCheckin extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'checkin_time' => 'datetime',
    ];

    public function workshopDay()
    {
        return $this->belongsTo(WorkshopDay::class);
    }

    public function participant()
    {
        return $this->belongsTo(WorkshopAttendance::class, 'workshop_attendance_id');
    }
}
