<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
   protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];


    public function writtenBy()
    {
        return $this->belongsTo(User::class, 'written_by');
    }

    public function attendances()
    {
        return $this->hasMany(WorkshopAttendance::class);
    }
}
