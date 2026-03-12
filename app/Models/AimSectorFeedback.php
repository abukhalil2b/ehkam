<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class AimSectorFeedback extends Model
{
    protected $guarded = [];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function indicator()
    {
        return $this->belongsTo(Aim::class);
    }

}
