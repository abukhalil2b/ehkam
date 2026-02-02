<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopAttendance extends Model
{
    protected $guarded = [];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function checkins()
    {
        return $this->hasMany(WorkshopCheckin::class, 'workshop_attendance_id');
    }
}
