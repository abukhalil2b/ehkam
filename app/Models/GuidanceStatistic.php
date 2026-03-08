<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuidanceStatistic extends Model
{
    protected $guarded = [];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function wilayat()
    {
        return $this->belongsTo(Wilayat::class);
    }
}