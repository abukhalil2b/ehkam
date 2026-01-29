<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KpiIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'unit',
        'currency',
        'description',
        'category',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get all values for this indicator.
     */
    public function values(): HasMany
    {
        return $this->hasMany(KpiValue::class);
    }

    /**
     * Get annual reports for this indicator.
     */
    public function annualReports(): HasMany
    {
        return $this->hasMany(KpiAnnualReport::class);
    }

    /**
     * Get values for a specific year.
     */
    public function valuesForYear(int $year)
    {
        return $this->values()->where('year', $year)->orderBy('quarter')->get();
    }

    /**
     * Get total target for a year.
     */
    public function getYearlyTarget(int $year): float
    {
        return $this->values()->where('year', $year)->sum('target_value');
    }

    /**
     * Get total actual for a year.
     */
    public function getYearlyActual(int $year): float
    {
        return $this->values()->where('year', $year)->sum('actual_value');
    }

    /**
     * Get achievement percentage for a year.
     */
    public function getAchievementPercentage(int $year): float
    {
        $target = $this->getYearlyTarget($year);
        if ($target == 0) return 0;
        
        $actual = $this->getYearlyActual($year);
        return round(($actual / $target) * 100, 2);
    }

    /**
     * Format value based on unit type.
     */
    public function formatValue(float $value): string
    {
        if ($this->unit === 'currency') {
            return number_format($value, 2) . ' ' . ($this->currency ?? 'ر.ع.');
        }
        
        if ($this->unit === 'percentage') {
            return number_format($value, 2) . '%';
        }
        
        return number_format($value);
    }

    /**
     * Scope for active indicators.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('id');
    }
}
