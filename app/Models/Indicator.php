<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function targets()
    {
        return $this->hasMany(IndicatorTarget::class);
    }

    public function achieved()
    {
        return $this->hasMany(IndicatorAchievement::class);
    }

    public function sectors_with_baseline()
    {
        return $this->belongsToMany(Sector::class, 'indicator_sector')
            ->withPivot('baseline_numeric', 'baseline_year')
            ->withTimestamps();
    }

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


    protected function sectorsCollection(): Attribute
    {
        return Attribute::get(function () {
            $sectors = $this->sectors;

            // إذا كانت string تحتوي على JSON
            if (is_string($sectors)) {
                $sectors = json_decode($sectors, true);
            }

            // تأكد أنها array
            $sectors = is_array($sectors) ? $sectors : [];

            return Sector::whereIn('id', $sectors)->get();
        });
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class);
    }
}
