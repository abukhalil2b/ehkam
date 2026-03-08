<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EndowmentStatistic extends Model
{
    protected $guarded = [];

    // الإحصائية تنتمي لمؤسسة وقفية واحدة
    public function endowment()
    {
        return $this->belongsTo(Endowment::class);
    }
}