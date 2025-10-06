<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\Choice;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class QuestionnaireController extends Controller
{
    public function index()
    {
        $questionnaires = Questionnaire::withCount('questions')
            ->latest()
            ->get();
        return view('questionnaire.index', compact('questionnaires'));
    }

    public function create()
    {
        return view('questionnaire.create');
    }


    public function store(Request $request)
    {
        // return $request->all();
        // basic shape validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'is_active' => 'boolean',
            'note_attachment' => 'nullable|boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:single,multiple,range,text,date',
            'questions.*.description' => 'nullable|string',
            'questions.*.ordered' => 'nullable|integer',

            // Only single/multiple need choices
            'questions.*.choices' => 'sometimes|array',
            'questions.*.choices.*.choice_text' => 'nullable|string',

            // Range questions: min/max values
            'questions.*.min_value' => 'nullable|integer',
            'questions.*.max_value' => 'nullable|integer',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // additional conditional checks for each question
        $questions = $request->input('questions', []);

        foreach ($questions as $i => $q) {
            $type = $q['type'] ?? null;

            // Single / multiple choice validation
            if (in_array($type, ['single', 'multiple'])) {
                if (empty($q['choices']) || !is_array($q['choices'])) {
                    return redirect()->back()
                        ->withErrors(["questions.$i.choices" => "خيارات مطلوبة للأسئلة من نوع اختيار فردي/متعدد."])
                        ->withInput();
                }

                foreach ($q['choices'] as $j => $c) {
                    $hasText = isset($c['choice_text']) && trim($c['choice_text']) !== '';
                    if (!$hasText) {
                        return redirect()->back()
                            ->withErrors(["questions.$i.choices.$j" => "يجب أن يحتوي الخيار على نص."])
                            ->withInput();
                    }
                }
            }

            // Range question validation
            if ($type === 'range') {
                $min = $q['min_value'] ?? null;
                $max = $q['max_value'] ?? null;

                if (!is_numeric($min) || !is_numeric($max)) {
                    return redirect()->back()
                        ->withErrors(["questions.$i" => "أسئلة المقياس تتطلب قيم min_value و max_value."])
                        ->withInput();
                }

                if ((int)$min > (int)$max) {
                    return redirect()->back()
                        ->withErrors(["questions.$i" => "الحد الأدنى يجب أن يكون أصغر أو يساوي الحد الأعلى."])
                        ->withInput();
                }
            }
        }


        // persist
        DB::transaction(function () use ($request, $questions) {
            $questionnaire = Questionnaire::create([
                'title' => $request->input('title'),
                'is_active' => $request->boolean('is_active'),
            ]);

            foreach ($questions as $index => $q) {
                $question = Question::create([
                    'questionnaire_id' => $questionnaire->id,
                    'type' => $q['type'],
                    'question_text' => $q['question_text'],
                    'description' => $q['description'] ?? null,
                    'min_value' => $q['min_value'] ?? null,
                    'max_value' => $q['max_value'] ?? null,
                    'note_attachment' => isset($q['note_attachment']) && $q['note_attachment'] ? true : false,
                    'ordered' => $q['ordered'] ?? $index,
                ]);


                if (in_array($q['type'], ['single', 'multiple']) && !empty($q['choices'])) {
                    foreach ($q['choices'] as $cIndex => $c) {
                        Choice::create([
                            'question_id' => $question->id,
                            'choice_text' => $c['choice_text'] ?? null,
                            'ordered' => $cIndex,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('questionnaire.index')->with('success', 'تم إنشاء الاستبيان بنجاح.');
    }

    public function show(Questionnaire $questionnaire)
    {
        $questionnaire->load([
            'questions.choices',
        ])->loadCount([
            'questions',
        ]);

        return view('questionnaire.show', compact('questionnaire'));
    }

    public function duplicate(Questionnaire $questionnaire)
    {
        // 1. Duplicate the questionnaire itself
        $copy = $questionnaire->replicate();
        $copy->title = $questionnaire->title . ' (نسخة)';
        $copy->is_active = false; // optional: make new one inactive by default
        $copy->push(); // saves the copy

        // 2. Duplicate its related questions
        foreach ($questionnaire->questions as $question) {
            $newQuestion = $question->replicate();
            $newQuestion->questionnaire_id = $copy->id;
            $newQuestion->push();
        }

        // 3. Redirect with success message
        return redirect()
            ->route('questionnaire.index')
            ->with('success', 'تم إنشاء نسخة من الاستبيان مع الأسئلة بنجاح.');
    }


    public function take(Questionnaire $questionnaire)
    {
        $user = auth()->user();

        if (!$questionnaire->is_active) {
            return redirect()->back()->with('error', 'هذه الاستمارة معطلة.');
        }

        // Check if user already answered
        $answered = Answer::where('questionnaire_id', $questionnaire->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($answered) {
            return redirect()->back()->with('error', 'لقد قمت بالإجابة على هذا الاستبيان مسبقاً.');
        }

        $questionnaire->load(['questions.choices' => function ($query) {
            $query->orderBy('ordered');
        }]);

        return view('questionnaire.take', compact('questionnaire'));
    }

    public function submit(Request $request, Questionnaire $questionnaire)
    {
        $user = auth()->user();

        $alreadyAnswered = Answer::where('questionnaire_id', $questionnaire->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyAnswered) {
            return abort(403, 'لقد قمت بالإجابة على هذا الاستبيان مسبقاً.');
        }


        // Loop through each question in this questionnaire
        foreach ($questionnaire->questions as $question) {

            // Build input field names (matching your form field names)
            $inputName = "question_{$question->id}";
            $noteName  = "note_{$question->id}";

            // Skip this question if user didn’t answer it
            if (!$request->has($inputName)) {
                continue;
            }

            // 🗂️ Prepare base answer data
            $answerData = [
                'user_id'          => $user->id,
                'questionnaire_id' => $questionnaire->id,
                'question_id'      => $question->id,
                'note'             => $request->input($noteName), // Optional note per question
            ];

            // Handle each question type accordingly
            switch ($question->type) {

                case 'text':
                case 'date':
                    // Save the answer as plain text (or date string)
                    $answerData['text_answer'] = $request->input($inputName);
                    break;

                case 'range':
                    // Save numeric range value (e.g. 1–5)
                    $answerData['range_value'] = intval($request->input($inputName));
                    break;

                case 'single':
                    // Save a single choice (wrapped in JSON for consistency)
                    $choiceId = $request->input($inputName)[0] ?? null;
                    $answerData['choice_ids'] = $choiceId
                        ? json_encode([$choiceId])
                        : null;
                    break;

                case 'multiple':
                    // Save multiple choices as a JSON array
                    $choiceIds = $request->input($inputName, []);
                    $answerData['choice_ids'] = json_encode($choiceIds);
                    break;
            }

            //Store the answer record
            Answer::create($answerData);
        }

        //  Redirect back with a success message
        return redirect()
            ->route('questionnaire.show', $questionnaire->id)
            ->with('success', 'تم إرسال الإجابات بنجاح');
    }


    public function edit(Questionnaire $questionnaire)
    {
        // Load questions and their choices
        $questionnaire->load('questions.choices');

        return view('questionnaire.edit', compact('questionnaire'));
    }


    public function update(Request $request, Questionnaire $questionnaire)
    {
        // return $request->all();
        // Validate basic structure
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'is_active' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:single,multiple,range,text,date',
            'questions.*.description' => 'nullable|string',
            'questions.*.ordered' => 'nullable|integer',
            'questions.*.note_attachment' => 'nullable|boolean',

            // Only single/multiple need choices
            'questions.*.choices' => 'sometimes|array',
            'questions.*.choices.*.choice_text' => 'nullable|string',

            // Range questions: min/max values
            'questions.*.min_value' => 'nullable|integer',
            'questions.*.max_value' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $questions = $request->input('questions', []);

        // Conditional validations
        foreach ($questions as $i => $q) {
            $type = $q['type'] ?? null;

            // Single / multiple choice validation
            if (in_array($type, ['single', 'multiple'])) {
                if (empty($q['choices']) || !is_array($q['choices'])) {
                    return redirect()->back()
                        ->withErrors(["questions.$i.choices" => "خيارات مطلوبة للأسئلة من نوع اختيار فردي/متعدد."])
                        ->withInput();
                }

                foreach ($q['choices'] as $j => $c) {
                    $hasText = isset($c['choice_text']) && trim($c['choice_text']) !== '';
                    if (!$hasText) {
                        return redirect()->back()
                            ->withErrors(["questions.$i.choices.$j" => "يجب أن يحتوي الخيار على نص."])
                            ->withInput();
                    }
                }
            }

            // Range question validation
            if ($type === 'range') {
                $min = $q['min_value'] ?? null;
                $max = $q['max_value'] ?? null;

                if (!is_numeric($min) || !is_numeric($max)) {
                    return redirect()->back()
                        ->withErrors(["questions.$i" => "أسئلة المقياس تتطلب قيم min_value و max_value."])
                        ->withInput();
                }

                if ((int)$min > (int)$max) {
                    return redirect()->back()
                        ->withErrors(["questions.$i" => "الحد الأدنى يجب أن يكون أصغر أو يساوي الحد الأعلى."])
                        ->withInput();
                }
            }
        }

        // Persist updates inside transaction
        DB::transaction(function () use ($questionnaire, $questions, $request) {

            // Update questionnaire
            $questionnaire->update([
                'title' => $request->input('title'),
                'is_active' => $request->boolean('is_active'),
            ]);

            // Delete old questions and choices
            foreach ($questionnaire->questions as $oldQuestion) {
                $oldQuestion->choices()->delete();
            }
            $questionnaire->questions()->delete();

            // Insert new/updated questions
            foreach ($questions as $index => $q) {
                $question = Question::create([
                    'questionnaire_id' => $questionnaire->id,
                    'type' => $q['type'],
                    'question_text' => $q['question_text'],
                    'description' => $q['description'] ?? null,
                    'min_value' => $q['min_value'] ?? null,
                    'max_value' => $q['max_value'] ?? null,
                    'note_attachment' => isset($q['note_attachment']) && $q['note_attachment'] ? true : false,
                    'ordered' => $q['ordered'] ?? $index,
                ]);

                // Only create choices for single/multiple
                if (in_array($q['type'], ['single', 'multiple']) && !empty($q['choices'])) {
                    foreach ($q['choices'] as $cIndex => $c) {
                        Choice::create([
                            'question_id' => $question->id,
                            'choice_text' => $c['choice_text'] ?? null,
                            'ordered' => $cIndex,
                        ]);
                    }
                }

                // ✅ No choices for range questions
            }
        });

        return redirect()->route('questionnaire.index')->with('success', 'تم تحديث الاستبيان بنجاح.');
    }


    public function delete(Questionnaire $questionnaire)
    {
        // Optionally, you can check permission here, e.g.,
        // $this->authorize('delete', $questionnaire);

        $questionnaire->delete(); // this will also delete questions and choices if you have cascading

        return redirect()->route('questionnaire.index')
            ->with('success', 'تم حذف الاستبيان بنجاح.');
    }


    public function answerShow(Answer $answer)
    {
        $user = auth()->user();

        // Only questionnaire owner or admin can access
        if ($user->id !== $answer->questionnaire->created_by && $user->id != 1) {
            abort(403, 'غير مصرح لك بعرض هذه الإجابة.');
        }

        // Load related data for display
        $answer->load(['question', 'questionnaire', 'user']);

        return view('questionnaire.answer_show', compact('answer'));
    }

    public function updateAnswer(Request $request, Answer $answer)
    {
        $user = auth()->user();

        // Only questionnaire owner can update answers
        if ($user->id !== $answer->questionnaire->created_by && $user->id != 1) {
            abort(403);
        }

        $question = $answer->question;

        $data = [
            'note' => $request->input('note'),
            'text_answer' => null,
            'range_value' => null,
            'choice_ids' => null,
        ];

        switch ($question->type) {
            case 'text':
            case 'date':
                $data['text_answer'] = $request->input('text_answer');
                break;

            case 'range':
                $data['range_value'] = intval($request->input('range_value'));
                break;

            case 'single':
                $choiceId = $request->input('choice_ids')[0] ?? null;
                $data['choice_ids'] = $choiceId ? json_encode([$choiceId]) : null;
                break;

            case 'multiple':
                $choiceIds = $request->input('choice_ids', []);
                $data['choice_ids'] = json_encode($choiceIds);
                break;
        }

        $answer->update($data);

        return redirect()
            ->route('questionnaire.show', $answer->questionnaire_id)
            ->with('success', 'تم تحديث الإجابة بنجاح');
    }


    public function destroyAnswer(Answer $answer)
    {
        $user = auth()->user();

        if ($user->id !== $answer->questionnaire->created_by && $user->id != 1) {
            abort(403);
        }

        $answer->delete();

        return redirect()
            ->route('questionnaire.show', $answer->questionnaire_id)
            ->with('success', 'تم حذف الإجابة بنجاح');
    }
}
