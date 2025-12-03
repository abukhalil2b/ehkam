<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicatorFeedbackValue extends Model
{
    protected $guarded = [];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    /**
     * Get the user who created this value.
     */
    public function createdBy(): BelongsTo
    {
        // Assuming you have a User model
        return $this->belongsTo(User::class, 'createdby_user_id');
    }
}
