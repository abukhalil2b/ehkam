<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicatorAchievement extends Model
{
    protected $guarded = [];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
}
