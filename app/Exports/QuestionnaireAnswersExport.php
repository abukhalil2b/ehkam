<?php

namespace App\Exports;

use App\Models\Questionnaire;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuestionnaireAnswersExport implements FromCollection, WithHeadings
{
    protected $questionnaire;
    protected $questions;

    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
        $this->questions = $questionnaire->questions()
            ->with('choices')
            ->orderBy('ordered')
            ->get();
    }

    public function headings(): array
    {
        $headings = ['المستخدم (User)', 'تاريخ الإجابة (Submission Date)'];

        foreach ($this->questions as $question) {
            $headings[] = $question->question_text;
            if ($question->note_attachment) {
                $headings[] = $question->question_text . ' - ملحوظة';
            }
        }

        return $headings;
    }

    public function collection()
{
    $groupedAnswers = $this->questionnaire->answers()
        ->with(['user', 'question.choices'])
        ->orderBy('created_at')
        ->get()
        ->groupBy(function ($answer) {
            return $answer->user_id . '_' . $answer->created_at->format('Y-m-d H:i:s');
        });

    $exportData = new Collection();

    foreach ($groupedAnswers as $group) {
        $user = $group->first()->user;
        $userId = $group->first()->user_id;
        $userName = $user ? $user->name . " (ID: {$userId})" : 'مستخدم مجهول';
        $submissionDate = optional($group->first()->created_at)->format('Y-m-d H:i');

        $row = [$userName, $submissionDate];

        $answersMap = $group->keyBy('question_id');

        foreach ($this->questions as $question) {
            $answer = $answersMap->get($question->id);
            $answerText = '';
            $noteText = '';

            if ($answer) {
                $noteText = $answer->note ?? '';

                switch ($question->type) {
                    case 'text':
                    case 'date':
                        $answerText = $answer->text_answer ?? '';
                        break;

                    case 'range':
                        $answerText = $answer->range_value ?? '';
                        break;

                    case 'single':
                    case 'multiple':
                        $choiceIds = json_decode($answer->choice_ids ?? '[]', true);
                        $answerText = $question->choices
                            ->whereIn('id', $choiceIds)
                            ->pluck('choice_text')
                            ->implode(', ');
                        break;
                }
            }

            $row[] = $answerText;
            if ($question->note_attachment) {
                $row[] = $noteText;
            }
        }

        $exportData->push($row);
    }

    return $exportData;
}

}
