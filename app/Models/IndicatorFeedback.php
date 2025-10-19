<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndicatorFeedback extends Model
{
    protected $guarded = [];

    /**
     * Get the indicator this feedback belongs to.
     */
    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class, 'indicator_id');
    }

    /**
     * Get the sector this feedback is assigned to.
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    /**
     * Get the values (targets/achievements) for this feedback entry.
     */
    public function values(): HasMany
    {
        return $this->hasMany(IndicatorFeedbackValue::class);
    }
}