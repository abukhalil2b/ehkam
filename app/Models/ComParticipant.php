<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComParticipant extends Model
{
    protected $fillable = [
        'competition_id',
        'name',
        'ip_address',
        'score',
        'current_question_id',
        'question_started_at',
        'auto_mode'
    ];

    protected $casts = [
        'score' => 'integer',
        'question_started_at' => 'datetime',
        'auto_mode' => 'boolean'
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

    // Auto-sequential mode helpers
    public function currentQuestion()
    {
        return $this->belongsTo(ComQuestion::class, 'current_question_id');
    }

    public function advanceToNextQuestion(): ?ComQuestion
    {
        $questions = $this->competition->questions()->orderBy('order')->get();
        
        // Find current position
        $currentIndex = $questions->search(function ($q) {
            return $q->id === $this->current_question_id;
        });
        
        if ($currentIndex === false) {
            // Start from first question
            $nextQuestion = $questions->first();
        } elseif ($currentIndex < $questions->count() - 1) {
            $nextQuestion = $questions[$currentIndex + 1];
        } else {
            // No more questions
            return null;
        }
        
        $this->update([
            'current_question_id' => $nextQuestion->id,
            'question_started_at' => now()
        ]);
        
        return $nextQuestion;
    }

    public function getTimeRemainingAttribute(): int
    {
        if (!$this->question_started_at) {
            return 0;
        }
        
        $elapsed = now()->diffInSeconds($this->question_started_at);
        return max(0, 30 - $elapsed);
    }

    public function isCompetitionFinished(): bool
    {
        $totalQuestions = $this->competition->questions()->count();
        $answeredQuestions = $this->answers()->distinct()->count();
        
        return $answeredQuestions >= $totalQuestions;
    }
}
