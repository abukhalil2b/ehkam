<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];
    public function activities()
    {
        // A Project has many Activities. The 'activities' table must contain the foreign key 'project_id'.
        return $this->hasMany(Activity::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function indicator(){
        return $this->belongsTo(Indicator::class);
    }
}
