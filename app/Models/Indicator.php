<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Indicator extends Model
{
    protected $guarded = [];


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

    public function sectors()
    {
        return $this->belongsToMany(
            Sector::class,
            'indicator_sector'
        )->withPivot('baseline_numeric', 'baseline_year')
            ->withTimestamps();
    }

    protected function periodLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->period) {
                'quarterly'   => 'ربع سنوي',
                'half_yearly' => 'نصف سنوي',
                'monthly'     => 'شهري',
                default       => 'سنوي',
            }
        );
    }
    
    public function visionItem()
    {
        return $this->belongsTo(VisionItem::class, 'vision_item_id');
    }
    
}
