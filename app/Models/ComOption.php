<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComOption extends Model
{
    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    // Relationships
    public function question()
    {
        return $this->belongsTo(ComQuestion::class, 'question_id');
    }

    public function answers()
    {
        return $this->hasMany(ComAnswer::class, 'option_id');
    }

    // Helper Methods
    public function markAsCorrect(): bool
    {
        // First, unmark all other options for this question
        $this->question->options()->update(['is_correct' => false]);
        
        // Then mark this as correct
        return $this->update(['is_correct' => true]);
    }

    public function getSelectionCount(): int
    {
        return $this->answers()->count();
    }

    public function getSelectionPercentage(): float
    {
        $totalAnswers = $this->question->getTotalAnswersCount();
        
        if ($totalAnswers === 0) {
            return 0;
        }
        
        return ($this->getSelectionCount() / $totalAnswers) * 100;
    }
}
