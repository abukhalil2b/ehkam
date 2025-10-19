<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    // --- Relationships ---

    /**
     * The users that belong to the sector (many-to-many).
     */
    public function users(): BelongsToMany
    {
        // Eloquent will automatically look for the 'sector_user' pivot table
        // and the foreign keys 'sector_id' and 'user_id'.
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the feedback entries that target this sector.
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(IndicatorFeedback::class);
    }
}