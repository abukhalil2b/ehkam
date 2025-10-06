<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];
    
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class)->orderBy('ordered');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
