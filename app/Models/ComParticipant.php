<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComParticipant extends Model
{
    protected $fillable = [
        'competition_id',
        'name',
        'ip_address',
        'score'
    ];

    protected $casts = [
        'score' => 'integer'
    ];

    // Relationships
    public function competition()
    {
        return $this->belongsTo(ComCompetition::class, 'competition_id');
    }

    public function answers()
    {
        return $this->hasMany(ComAnswer::class, 'participant_id');
    }

    // Helper Methods
    public function correctAnswersCount(): int
    {
        return $this->answers()->where('is_correct', true)->count();
    }

    public function incorrectAnswersCount(): int
    {
        return $this->answers()->where('is_correct', false)->count();
    }

    public function totalAnswersCount(): int
    {
        return $this->answers()->count();
    }

    public function getAccuracy(): float
    {
        $total = $this->totalAnswersCount();
        
        if ($total === 0) {
            return 0;
        }
        
        return ($this->correctAnswersCount() / $total) * 100;
    }

    public function incrementScore(): bool
    {
        return $this->increment('score');
    }

    public function hasAnsweredQuestion(int $questionId): bool
    {
        return $this->answers()
            ->where('question_id', $questionId)
            ->exists();
    }

    public function getAnswerForQuestion(int $questionId): ?ComAnswer
    {
        return $this->answers()
            ->where('question_id', $questionId)
            ->first();
    }

    public function getRank(): int
    {
        return $this->competition->participants()
            ->where('score', '>', $this->score)
            ->count() + 1;
    }
}
