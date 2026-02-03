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

    /**
     * Check if an IP already has a checkin for a specific day
     */
    public static function existsForDayAndIp(int $dayId, string $ip): bool
    {
        return static::where('workshop_day_id', $dayId)
            ->where('ip_address', $ip)
            ->exists();
    }

    /**
     * Get checkin by day and IP
     */
    public static function findByDayAndIp(int $dayId, string $ip): ?self
    {
        return static::where('workshop_day_id', $dayId)
            ->where('ip_address', $ip)
            ->first();
    }

    public function workshopDay()
    {
        return $this->belongsTo(WorkshopDay::class);
    }

    public function participant()
    {
        return $this->belongsTo(WorkshopAttendance::class, 'workshop_attendance_id');
    }
}
