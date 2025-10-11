<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentQuestion extends Model
{
    public $timestamps = false;
    
    protected $guarded = [];
    
    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }
}
