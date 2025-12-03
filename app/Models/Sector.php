<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}