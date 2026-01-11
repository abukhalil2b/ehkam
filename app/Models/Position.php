<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_code',
        'title',
        'reports_to_position_id',
        'ordered',
    ];

    protected $casts = [
        'ordered' => 'integer',
    ];

    /**
     * Get the position this position reports to.
     */
    public function reportsTo(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'reports_to_position_id');
    }

    /**
     * Get all positions that report to this position.
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Position::class, 'reports_to_position_id');
    }

    /**
     * Get all organizational units this position belongs to.
     */
    public function orgUnits(): BelongsToMany
    {
        return $this->belongsToMany(OrgUnit::class, 'org_unit_positions');
    }

    /**
     * Get all employee assignments for this position.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(EmployeeAssignment::class);
    }

    /**
     * Get current active employee assignments.
     */
    public function currentEmployees(): HasMany
    {
        return $this->employees()
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Check if the position is currently vacant.
     */
    public function getIsVacantAttribute(): bool
    {
        return $this->currentEmployees()->count() === 0;
    }

    /**
     * Get the reporting chain for this position.
     */
    public function getReportingChainAttribute(): array
    {
        $chain = [];
        $current = $this;

        while ($current->reportsTo) {
            $chain[] = $current->reportsTo;
            $current = $current->reportsTo;
        }

        return $chain;
    }
}