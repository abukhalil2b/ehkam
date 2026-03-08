<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuranSchoolStatistic extends Model
{
    protected $guarded = [];

    // الإحصائية تنتمي لمحافظة
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    // الإحصائية تنتمي لولاية (وقد تكون null إذا كانت الإحصائية شاملة للمحافظة)
    public function wilayat()
    {
        return $this->belongsTo(Wilayat::class);
    }
}