<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicatorTarget extends Model
{
    protected $guarded = [];


    protected $casts = [
        'current_year' => 'integer',
    ];

    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indicator_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
