<?php
namespace App\Livewire\Questionnaire;

use App\Models\Questionnaire;
use Livewire\Component;

class EditQuestions extends Component
{
    public Questionnaire $questionnaire;

    // This is the magic method from the sortable plugin
    public function updateQuestionOrder($list)
    {
        foreach ($list as $item) {
            $this->questionnaire->questions()
                ->find($item['value'])
                ->update(['ordered' => $item['order']]);
        }

        // Refresh the questions collection to reflect the new order
        $this->questionnaire->refresh();
        
        // Optional: show a success message
        $this->dispatchBrowserEvent('notify', 'تم تحديث ترتيب الأسئلة بنجاح!');
    }

    public function render()
    {
        // Eager load questions in the correct order
        $questions = $this->questionnaire->questions()->orderBy('ordered')->get();
        return view('livewire.questionnaire.edit-questions', compact('questions'));
    }
}