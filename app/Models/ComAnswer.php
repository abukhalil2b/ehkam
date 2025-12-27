<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComAnswer extends Model
{
    protected $fillable = [
        'participant_id',
        'question_id',
        'option_id',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($answer) {
            // Automatically set is_correct based on the option
            $option = ComOption::find($answer->option_id);
            $answer->is_correct = $option->is_correct;
        });

        static::created(function ($answer) {
            // Automatically update participant score if correct
            if ($answer->is_correct) {
                $answer->participant->incrementScore();
            }
        });
    }

    // Relationships
    public function participant()
    {
        return $this->belongsTo(ComParticipant::class, 'participant_id');
    }

    public function question()
    {
        return $this->belongsTo(ComQuestion::class, 'question_id');
    }

    public function option()
    {
        return $this->belongsTo(ComOption::class, 'option_id');
    }

    // Helper Methods
    public function getResponseTimeAttribute(): ?int
    {
        $competition = $this->question->competition;
        
        if (!$competition->question_started_at) {
            return null;
        }
        
        return $this->created_at->diffInSeconds($competition->question_started_at);
    }
}