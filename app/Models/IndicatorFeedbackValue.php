<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicatorFeedbackValue extends Model
{
    protected $guarded = [];

    /**
     * Get the feedback entry this value belongs to.
     */
    public function feedback(): BelongsTo
    {
        return $this->belongsTo(IndicatorFeedback::class, 'indicator_feedback_id');
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