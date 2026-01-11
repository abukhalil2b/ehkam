<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position_id',
        'org_unit_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user (employee) for this assignment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the position for this assignment.
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the organizational unit for this assignment.
     */
    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }

    /**
     * Scope to get only active assignments.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('end_date')
                ->orWhere('end_date', '>=', now());
        })->where('start_date', '<=', now());
    }

    /**
     * Scope to get only ended assignments.
     */
    public function scopeEnded($query)
    {
        return $query->whereNotNull('end_date')
            ->where('end_date', '<', now());
    }

    /**
     * Check if the assignment is currently active.
     */
    public function getIsActiveAttribute(): bool
    {
        $now = now();
        $started = $this->start_date <= $now;
        $notEnded = is_null($this->end_date) || $this->end_date >= $now;

        return $started && $notEnded;
    }

    /**
     * Get the duration of the assignment in days.
     */
    public function getDurationAttribute(): ?int
    {
        if (is_null($this->end_date)) {
            return null;
        }

        return $this->start_date->diffInDays($this->end_date);
    }
}