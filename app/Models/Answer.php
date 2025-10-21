<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'choice_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Accessor for choices (since we use JSON IDs)
    public function getChoicesAttribute()
    {
        if (!$this->choice_ids) return collect();
        return Choice::whereIn('id', $this->choice_ids)->orderBy('ordered')->get();
    }
    
}
