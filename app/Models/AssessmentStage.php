<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentStage extends Model
{
     protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }
}
