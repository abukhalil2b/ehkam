<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $guarded = [];


    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('ordered');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getIsOpenForAllAttribute(): bool
    {
        return $this->target_response === 'open_for_all';
    }
    
}
