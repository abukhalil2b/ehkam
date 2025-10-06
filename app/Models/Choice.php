<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
     protected $guarded = [];

     public function question()
     {
          return $this->belongsTo(Question::class);
     }

     public function answers()
     {
          return Answer::whereJsonContains('choice_ids', $this->id);
     }
}
