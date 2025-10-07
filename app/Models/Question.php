<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    protected $casts = [
        'min_value' => 'integer',
        'max_value' => 'integer',
        'note_attachment' => 'boolean'
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class)->orderBy('ordered');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getAnswerStatistics()
    {
        $answers = $this->answers;

        switch ($this->type) {
            case 'single':
            case 'multiple':
                return $this->getChoiceStatistics($answers);
            case 'range':
                return $this->getRangeStatistics($answers);
            case 'text':
                return $this->getTextStatistics($answers);
            case 'date':
                return $this->getDateStatistics($answers);
        }
    }

    private function getChoiceStatistics($answers)
    {
        $statistics = [];
        $totalAnswers = $answers->count();

        foreach ($this->choices as $choice) {
            $count = $answers->filter(function ($answer) use ($choice) {
                return in_array($choice->id, $answer->choice_ids ?? []);
            })->count();

            $percentage = $totalAnswers > 0 ? ($count / $totalAnswers) * 100 : 0;

            $statistics[] = [
                'choice' => $choice->choice_text,
                'count' => $count,
                'percentage' => round($percentage, 1)
            ];
        }

        return [
            'type' => 'choice',
            'total' => $totalAnswers,
            'data' => $statistics
        ];
    }

    private function getRangeStatistics($answers)
    {
        $rangeAnswers = $answers->pluck('range_value')->filter();

        return [
            'type' => 'range',
            'total' => $rangeAnswers->count(),
            'average' => $rangeAnswers->avg(),
            'min' => $rangeAnswers->min(),
            'max' => $rangeAnswers->max(),
            'distribution' => $this->getRangeDistribution($rangeAnswers)
        ];
    }

    private function getRangeDistribution($answers)
    {
        if ($this->min_value === null || $this->max_value === null) {
            return [];
        }

        $distribution = [];
        for ($i = $this->min_value; $i <= $this->max_value; $i++) {
            $count = $answers->filter(function ($value) use ($i) {
                return $value == $i;
            })->count();

            $distribution[] = [
                'value' => $i,
                'count' => $count
            ];
        }

        return $distribution;
    }

    private function getTextStatistics($answers)
    {
        $textAnswers = $answers->pluck('text_answer')->filter();
        $wordCounts = $textAnswers->map(function ($answer) {
            return str_word_count($answer);
        });

        return [
            'type' => 'text',
            'total' => $textAnswers->count(),
            'average_word_count' => $wordCounts->avg(),
            'sample_answers' => $textAnswers->take(5)->values()
        ];
    }

    private function getDateStatistics($answers)
    {
        // For date type, you might want to implement specific date analysis
        return [
            'type' => 'date',
            'total' => $answers->count(),
            'data' => $answers->pluck('text_answer')
        ];
    }
}
