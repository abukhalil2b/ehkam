<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $guarded = [];

    public function stepEvidenceFiles()
    {
        return $this->hasMany(StepEvidenceFile::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
