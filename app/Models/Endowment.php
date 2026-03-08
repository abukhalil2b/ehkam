<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endowment extends Model
{
    protected $guarded = [];

    // المؤسسة الوقفية تنتمي لمحافظة
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    // المؤسسة الوقفية لها عدة إحصائيات عبر السنوات
    public function statistics()
    {
        return $this->hasMany(EndowmentStatistic::class);
    }
}