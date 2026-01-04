<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AimSectorFeedback extends Model
{
    protected $guarded = [];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
    public function indicator()
    {
        return $this->belongsTo(Aim::class);
    }

    /**
     * Get the user who created this value.
     */
    public function createdBy()
    {
        // Assuming you have a User model
        return $this->belongsTo(User::class, 'createdby_user_id');
    }
}
