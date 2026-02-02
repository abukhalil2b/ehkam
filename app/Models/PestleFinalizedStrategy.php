<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PestleFinalizedStrategy extends Model
{
    protected $guarded = [];

    protected $casts = [
        'initiatives' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(PestleProject::class, 'pestle_project_id');
    }
}
