<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'kpi_indicator_id',
        'year',
        'quarter',
        'target_value',
        'actual_value',
        'justification',
        'notes',
    ];

    protected $casts = [
        'year' => 'integer',
        'quarter' => 'integer',
        'target_value' => 'decimal:2',
        'actual_value' => 'decimal:2',
    ];

    /**
     * Get the indicator for this value.
     */
    public function indicator(): BelongsTo
    {
        return $this->belongsTo(KpiIndicator::class, 'kpi_indicator_id');
    }

    /**
     * Get achievement percentage.
     */
    public function getAchievementPercentageAttribute(): float
    {
        if ($this->target_value == 0) return 0;
        return round(($this->actual_value / $this->target_value) * 100, 2);
    }

    /**
     * Get quarter name in Arabic.
     */
    public function getQuarterNameAttribute(): string
    {
        $names = [
            1 => 'الربع الأول',
            2 => 'الربع الثاني',
            3 => 'الربع الثالث',
            4 => 'الربع الرابع',
        ];
        return $names[$this->quarter] ?? '';
    }

    /**
     * Get full period label.
     */
    public function getPeriodLabelAttribute(): string
    {
        return $this->quarter_name . ' ' . $this->year;
    }

    /**
     * Scope for specific year.
     */
    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope for specific quarter.
     */
    public function scopeForQuarter($query, int $quarter)
    {
        return $query->where('quarter', $quarter);
    }
}
