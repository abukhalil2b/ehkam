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
        // Load questions in the correct order for column mapping
        $this->questions = $questionnaire->questions()->orderBy('ordered')->get();
    }

    /**
     * Define the column headers (Question Texts)
     */
    public function headings(): array
    {
        $headings = ['المستخدم (User)', 'تاريخ الإجابة (Submission Date)'];

        // Add each question's text as a column header
        foreach ($this->questions as $question) {
            $headings[] = $question->question_text;
            // If the question allows a note, add a separate column for it
            if ($question->note_attachment) {
                $headings[] = $question->question_text . ' - ملحوظة';
            }
        }
        
        return $headings;
    }

    /**
     * Retrieve and format the data collection
     */
    public function collection()
    {
        // 1. Group all answers by the user who submitted them
        $groupedAnswers = $this->questionnaire->answers()
            ->with(['user', 'question.choices'])
            ->get()
            ->groupBy('user_id');

        $exportData = new Collection();

        // 2. Iterate through each user's submission
        foreach ($groupedAnswers as $userId => $userAnswers) {
            $user = $userAnswers->first()->user;
            $submissionDate = $userAnswers->max('created_at')->format('Y-m-d H:i');
            
            // Start the row with User and Date
            $row = [
                $user->name . " (ID: {$userId})", 
                $submissionDate
            ];

            // 3. Map answers to the correct question order
            $answersMap = $userAnswers->keyBy('question_id');

            // 4. Fill the row based on the required question order
            foreach ($this->questions as $question) {
                $answer = $answersMap->get($question->id);
                $answerText = '';
                $noteText = null;

                if ($answer) {
                    $noteText = $answer->note;
                    
                    switch ($question->type) {
                        case 'text':
                            $answerText = $answer->text_answer;
                            break;
                            
                        case 'date':
                            $answerText = $answer->text_answer; // Assuming date is saved in text_answer
                            break;

                        case 'range':
                            $answerText = $answer->range_value;
                            break;

                        case 'single':
                        case 'multiple':
                            // Decode choice_ids and fetch choice texts
                            $choiceIds = json_decode($answer->choice_ids, true) ?? [];
                            
                            // Get the choice texts and join them with a comma
                            $choiceTexts = $question->choices
                                ->whereIn('id', $choiceIds)
                                ->pluck('choice_text')
                                ->implode(', ');

                            $answerText = $choiceTexts;
                            break;
                    }
                }

                // Add the main answer value
                $row[] = $answerText;

                // Add the note value if permitted by the question schema
                if ($question->note_attachment) {
                    $row[] = $noteText;
                }
            }
            
            $exportData->push($row);
        }

        return $exportData;
    }
}