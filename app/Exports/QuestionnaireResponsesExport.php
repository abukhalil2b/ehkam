<?php

namespace App\Exports;

use App\Models\Questionnaire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuestionnaireResponsesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $questionnaire;
    protected $questions;
    protected $answersGrouped;

    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
        $this->questions = $questionnaire->questions()->orderBy('ordered')->get();
        // Group all answers by the user who submitted them
        $this->answersGrouped = $questionnaire->answers()->with(['user', 'question', 'choices'])->get()->groupBy('user_id');
    }

    public function headings(): array
    {
        $headings = ['User Name', 'User Email', 'Submission Date'];
        foreach ($this->questions as $question) {
            $headings[] = $question->question_text;
        }
        return $headings;
    }

    public function map($userAnswers): array
    {
        $firstAnswer = $userAnswers->first();
        $row = [
            $firstAnswer->user->name,
            $firstAnswer->user->email,
            $firstAnswer->created_at->format('Y-m-d H:i'),
        ];

        foreach ($this->questions as $question) {
            // Find the specific answer for this question within the user's answers
            $answer = $userAnswers->firstWhere('question_id', $question->id);
            $row[] = $this->formatAnswer($answer);
        }

        return $row;
    }

    private function formatAnswer($answer): string
    {
        if (!$answer) return 'N/A';

        switch ($answer->question->type) {
            case 'text':
                return $answer->text_answer ?? '';
            case 'range':
                return $answer->range_value ?? '';
            case 'single':
            case 'multiple':
                return $answer->choices()->pluck('choice_text')->implode(', ');
            case 'date':
                 return $answer->text_answer ?? ''; // Assuming date is stored in text_answer
            default:
                return '';
        }
    }
    
    public function collection()
    {
        return $this->answersGrouped;
    }
}