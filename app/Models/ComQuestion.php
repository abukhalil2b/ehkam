<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComQuestion extends Model
{
    protected $fillable = [
        'competition_id',
        'question_text',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships
    public function competition()
    {
        return $this->belongsTo(ComCompetition::class, 'competition_id');
    }

    public function options()
    {
        return $this->hasMany(ComOption::class, 'question_id');
    }

    public function answers()
    {
        return $this->hasMany(ComAnswer::class, 'question_id');
    }

    public function correctOption()
    {
        return $this->hasOne(ComOption::class, 'question_id')->where('is_correct', true);
    }

    // Helper Methods
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    public function getCorrectAnswersCount(): int
    {
        return $this->answers()->where('is_correct', true)->count();
    }

    public function getIncorrectAnswersCount(): int
    {
        return $this->answers()->where('is_correct', false)->count();
    }

    public function getTotalAnswersCount(): int
    {
        return $this->answers()->count();
    }
}
