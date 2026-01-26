<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CalendarEvent extends Model
{
    protected $fillable = [
        'title',
        'type',
        'program',
        'start_date',
        'end_date',
        'year',
        'bg_color',
        'notes',
        'is_public',
        'user_id',
        'target_user_id',
        'hijri_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_public' => 'boolean',
    ];

    public function getContextAttribute()
    {
        if ($this->org_unit_id) {
            return 'org';
        }
        return 'user';
    }

    public function isOrganizational(): bool
    {
        return !is_null($this->org_unit_id);
    }

    public function getOwnerNameAttribute()
    {
        return $this->isOrganizational()
            ? $this->orgUnit->name
            : $this->targetUser->name;
    }

    // ========== RELATIONSHIPS ==========

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(CalendarAuditLog::class);
    }

    // ========== BOOT LIFECYCLE ==========

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($event) {
            if ($event->start_date) {
                $event->year = $event->start_date->year;
                $event->hijri_date = $event->generateHijriDate();
            }
        });

        static::saved(function ($event) {
            // Log the action
            CalendarAuditLog::create([
                'calendar_event_id' => $event->id,
                'user_id' => auth()->id(),
                'action' => $event->wasRecentlyCreated ? 'created' : 'updated',
                'old_data' => $event->getOriginal(),
                'new_data' => $event->getAttributes(),
            ]);
        });
    }

    // ========== COMPUTED ATTRIBUTES ==========

    /**
     * Generate Hijri date with fallback handling
     */
    protected function generateHijriDate(): string
    {
        return \App\Helpers\HijriDateHelper::format($this->start_date);
    }

    public function getHijriDateAttribute(): string
    {
        return $this->attributes['hijri_date'] ?? $this->generateHijriDate();
    }

    public function getDurationAttribute(): int
    {
        return $this->start_date->diffInMinutes($this->end_date);
    }

    public function getDurationHumanAttribute(): string
    {
        $minutes = $this->duration;

        if ($minutes < 60) {
            return "{$minutes} دقيقة";
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return $hours === 1 ? "ساعة واحدة" : "{$hours} ساعات";
        }

        return "{$hours} ساعة و {$remainingMinutes} دقيقة";
    }

    public function getStatusAttribute(): string
    {
        $now = now();

        if ($now->lt($this->start_date))
            return 'upcoming';
        if ($now->gt($this->end_date))
            return 'completed';

        return 'active';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => '#059669',
            'upcoming' => '#4b5563',
            'completed' => '#2563eb',
            default => '#6b7280'
        };
    }

    // ========== QUERY SCOPES ==========

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('target_user_id', $userId)
                ->orWhere('user_id', $userId)
                ->orWhere('is_public', true);
        });
    }

    public function scopeInYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeInDateRange($query, Carbon $start, Carbon $end)
    {
        return $query->where(function ($q) use ($start, $end) {
            $q->where('start_date', '<', $end)
                ->where('end_date', '>', $start);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    // ========== HELPER METHODS ==========

    /**
     * Check if this event conflicts with another
     */
    public function conflictsWith(CalendarEvent $other): bool
    {
        return $this->start_date->lt($other->end_date)
            && $this->end_date->gt($other->start_date)
            && $this->target_user_id === $other->target_user_id;
    }

    /**
     * Find all conflicting events for this event
     */
    public function findConflicts(?int $ignoreId = null): \Illuminate\Support\Collection
    {
        $query = static::where('target_user_id', $this->target_user_id)
            ->where('start_date', '<', $this->end_date)
            ->where('end_date', '>', $this->start_date);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->with('user')->get();
    }

    /**
     * Suggest next available time slot
     */

    public function suggestNextSlot(): ?Carbon
    {
        $duration = $this->duration;
        $proposedStart = $this->start_date->copy();

        // Safety break after 50 attempts
        $attempts = 0;

        while ($attempts < 50) {
            $attempts++;

            // 1. Move forward
            $proposedStart->addMinutes(30); // Check every 30 mins

            // 2. Skip Weekends (Friday/Saturday) - Adjust based on your country
            if ($proposedStart->isFriday() || $proposedStart->isSaturday()) {
                $proposedStart->addDay()->startOfDay()->setHour(8); // Reset to next work day 8 AM
                continue;
            }

            // 3. Skip Non-Work Hours (Before 8 AM or After 2 PM)
            if ($proposedStart->hour < 8) {
                $proposedStart->setHour(8)->setMinute(0);
            }
            if ($proposedStart->hour >= 14) {
                $proposedStart->addDay()->setHour(8)->setMinute(0);
                continue;
            }

            $proposedEnd = $proposedStart->copy()->addMinutes($duration);

            // 4. Check Conflict
            $conflict = static::where('target_user_id', $this->target_user_id)
                ->where('start_date', '<', $proposedEnd)
                ->where('end_date', '>', $proposedStart)
                ->exists();

            if (!$conflict) {
                return $proposedStart;
            }
        }

        return null;
    }
}
