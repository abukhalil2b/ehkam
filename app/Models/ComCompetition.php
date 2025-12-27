<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ComCompetition extends Model
{
    protected $fillable = [
        'title',
        'status',
        'join_code',
        'current_question_id',
        'question_started_at'
    ];

    protected $casts = [
        'question_started_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($competition) {
            if (empty($competition->join_code)) {
                $competition->join_code = strtoupper(Str::random(6));
            }
        });
    }

    // Relationships
    public function questions()
    {
        return $this->hasMany(ComQuestion::class, 'competition_id');
    }

    public function participants()
    {
        return $this->hasMany(ComParticipant::class, 'competition_id');
    }

    public function currentQuestion()
    {
        return $this->belongsTo(ComQuestion::class, 'current_question_id');
    }

    // Accessors
    public function getJoinUrlAttribute(): string
    {
        return route('participant.competition.join', $this->join_code);
    }

    // Helper Methods
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function isStarted(): bool
    {
        return $this->status === 'started';
    }

    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    public function canAcceptParticipants(): bool
    {
        return $this->status === 'closed';
    }

    public function getTimeRemainingAttribute(): int
    {
        if (!$this->question_started_at) {
            return 0;
        }
        
        $elapsed = now()->diffInSeconds($this->question_started_at);
        return max(0, 30 - $elapsed);
    }
}