<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];
    // Relationship to get all assessment results for this activity
    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }

    // Relationship to the Project (assuming a Project model exists)
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Current active workflow state
    public function currentWorkflow()
    {
        return $this->hasOne(ActivityWorkflow::class)->latest();
    }
}
