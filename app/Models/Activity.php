<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_feed_indicator' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // ...
    }

    // ========== EXISTING RELATIONSHIPS ==========

    /**
     * Assessment results for this activity
     */
    public function assessmentResults(): HasMany
    {
        return $this->hasMany(AssessmentResult::class);
    }

    /**
     * The project this activity belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Steps associated with this activity
     */
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    /**
     * The user who created this activity
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * The employees assigned to evaluate this activity.
     */
    public function employees(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'activity_user_assign')
            ->withTimestamps();
    }
}
