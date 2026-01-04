<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aim extends Model
{
     protected $guarded = [];
   
    public function aimSectorFeedbackValues()
    {
         return $this->hasMany(AimSectorFeedback::class);
    }
}
