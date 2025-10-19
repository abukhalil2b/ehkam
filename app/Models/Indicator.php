<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Indicator extends Model
{
    // Allows mass assignment for all fields except the reserved ones.
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     * The 'sectors' column should be cast to an array/json.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sectors' => 'array',
    ];

    // --- Relationships ---

    /**
     * Get the parent indicator (if it is a sub-indicator).
     */
    public function parent(): BelongsTo
    {
        // 'parent_id' is the foreign key on the current model (Indicator).
        // 'id' is the local key on the related model (Indicator).
        return $this->belongsTo(Indicator::class, 'parent_id');
    }

    /**
     * Get the sub-indicators (children) for this indicator.
     */
    public function children(): HasMany
    {
        // 'parent_id' is the foreign key on the related model (Indicator).
        // 'id' is the local key on the current model (Indicator).
        return $this->hasMany(Indicator::class, 'parent_id');
    }

    /**
     * Get the period template associated with the indicator.
     * NOTE: This assumes the 'period' column on indicators maps to either 'cate' or 'name' on period_templates.
     * If 'period' stores the ID of the template, you'd use BelongsTo with 'period'.
     */
    public function periodTemplate()
    {
        // If 'period' on indicators stores the PeriodTemplate name/cate:
        return $this->belongsTo(PeriodTemplate::class, 'period', 'name')
                    ->orWhere(function ($query) {
                        $query->whereColumn('indicators.period', 'period_templates.cate');
                    });

        // If 'period' on indicators stores the PeriodTemplate ID:
        // return $this->belongsTo(PeriodTemplate::class, 'period');
    }

    /**
     * Get all feedback entries for the indicator.
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(IndicatorFeedback::class);
    }

    /**
     * Get all sectors related to this indicator based on the 'sectors' JSON array.
     * NOTE: This is a complex relationship since 'sectors' is a JSON column, not a standard foreign key.
     * You might use a custom accessor or a scope for this in real applications, but a simple relationship isn't direct.
     * The logic in the controller's show method is the correct way to handle this.
     */
    public function relatedSectors()
    {
        return Sector::whereIn('id', Arr::wrap($this->sectors));
    }
}