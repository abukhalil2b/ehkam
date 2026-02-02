<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopDay extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'day_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function checkins()
    {
        return $this->hasMany(WorkshopCheckin::class);
    }
}
