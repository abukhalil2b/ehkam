<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkshopDay extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'day_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model and generate hash automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($day) {
            if (empty($day->attendance_hash)) {
                $day->attendance_hash = static::generateUniqueHash();
            }
        });
    }

    /**
     * Generate a unique attendance hash
     */
    public static function generateUniqueHash(): string
    {
        do {
            $hash = Str::random(64);
        } while (static::where('attendance_hash', $hash)->exists());

        return $hash;
    }

    /**
     * Regenerate the attendance hash
     */
    public function regenerateHash(): string
    {
        $this->attendance_hash = static::generateUniqueHash();
        $this->save();

        return $this->attendance_hash;
    }

    /**
     * Get the attendance registration URL
     */
    public function getAttendanceUrlAttribute(): string
    {
        return route('workshop.attend', $this->attendance_hash);
    }

    /**
     * Check if an IP has already checked in
     */
    public function hasIpCheckedIn(string $ip): bool
    {
        return $this->checkins()->where('ip_address', $ip)->exists();
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function checkins()
    {
        return $this->hasMany(WorkshopCheckin::class);
    }

    public function attendances()
    {
        return $this->belongsToMany(WorkshopAttendance::class, 'workshop_checkins')
            ->withPivot('status', 'checkin_time', 'ip_address')
            ->withTimestamps();
    }
}
