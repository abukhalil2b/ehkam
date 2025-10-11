<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
{
     protected $guarded = [];


     /**
      * Get the assessment question associated with the result.
      */
     public function assessmentQuestion()
     {
          // This links the result back to the AssessmentQuestion model
          // using the foreign key 'assessment_question_id'.
          return $this->belongsTo(AssessmentQuestion::class);
     }

     /**
      * Get the user who submitted the result.
      */
     public function user()
     {
          return $this->belongsTo(User::class);
     }

     /**
      * Get the activity the result belongs to.
      */
     public function activity()
     {
          return $this->belongsTo(Activity::class);
     }
}
