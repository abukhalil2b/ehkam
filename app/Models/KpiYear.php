<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'name',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'year' => 'integer',
    ];

    /**
     * Scope for active years.
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
        return $query->orderBy('display_order')->orderBy('year');
    }

    /**
     * Get years as array for dropdowns.
     */
    public static function getActiveYearsArray()
    {
        return static::active()->ordered()->pluck('year')->toArray();
    }

    /**
     * Toggle active status.
     */
    public function toggleActive()
    {
        $this->update(['is_active' => !$this->is_active]);
    }
}
