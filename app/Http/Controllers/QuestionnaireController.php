<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\Choice;
use App\Models\Answer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exports\QuestionnaireAnswersExport;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

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

        // 1. Basic Shape Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'is_active' => 'boolean',
            'target_response' => 'required|in:open_for_all,registerd_only', // Ensure target_response is validated
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            // Add 'dropdown' to the allowed types
            'questions.*.type' => ['required', Rule::in(['single', 'multiple', 'range', 'text', 'date', 'dropdown'])],
            'questions.*.ordered' => 'nullable|integer',

            // Fields for linked questions (temporary index from form)
            'questions.*.parent_question_index' => 'nullable', 

            // Choices and their dependency fields (sent from Alpine.js)
            'questions.*.choices' => 'sometimes|array',
            'questions.*.choices.*.choice_text' => 'nullable|string',
            'questions.*.choices.*.ordered' => 'nullable|integer',
            'questions.*.choices.*.parent_question_index' => 'nullable|integer',
            'questions.*.choices.*.parent_choice_index' => 'nullable|integer',

            // Range questions: min/max values with comparison
            'questions.*.min_value' => 'nullable|integer',
            'questions.*.max_value' => 'nullable|integer|gte:questions.*.min_value', // Max >= Min
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Conditional/Existence Validation (e.g., ensuring choices exist)
        $questions = $request->input('questions', []);

        foreach ($questions as $i => $q) {
            $type = $q['type'] ?? null;

            // Single / multiple / DROPDOWN choice validation: must have choices if non-text type
            if (in_array($type, ['single', 'multiple', 'dropdown'])) {
                if (empty($q['choices']) || !is_array($q['choices'])) {
                    return redirect()->back()
                        ->withErrors(["questions.$i.choices" => "خيارات مطلوبة للأسئلة من نوع اختيار فردي/متعدد/قائمة منسدلة."])
                        ->withInput();
                }

                // Ensure all choices have non-empty text
                foreach ($q['choices'] as $j => $c) {
                    $hasText = isset($c['choice_text']) && trim($c['choice_text']) !== '';
                    if (!$hasText) {
                        return redirect()->back()
                            ->withErrors(["questions.$i.choices.$j.choice_text" => "يجب أن يحتوي الخيار على نص."])
                            ->withInput();
                    }
                }
            }
        }


        // 3. Persistence (Three-Pass Logic)
        DB::transaction(function () use ($request, $questions) {

            // A. Create Questionnaire
            $questionnaire = Questionnaire::create([
                'title' => $request->input('title'),
                'is_active' => $request->boolean('is_active'),
                'target_response' => $request->input('target_response')
            ]);

            $questionIndexToIdMap = []; // Map: Form Array Index (0, 1, 2...) -> New Question ID
            $questionChoiceData = [];    // Store choice data for Passes 2 & 3

            // PASS 1: Create all Questions
            foreach ($questions as $index => $q) {
                $question = Question::create([
                    'questionnaire_id' => $questionnaire->id,
                    'type' => $q['type'],
                    'question_text' => $q['question_text'],
                    'min_value' => $q['min_value'] ?? null,
                    'max_value' => $q['max_value'] ?? null,
                    'ordered' => $q['ordered'] ?? $index,
                    'parent_question_id' => null, // Will be updated in Pass 2
                ]);

                // Map the form's temporary index to the newly created Question ID
                $questionIndexToIdMap[$index] = $question->id;

                // Store the original choice data for Pass 2 & 3
                $questionChoiceData[$index] = [
                    'question_id' => $question->id,
                    'choices' => $q['choices'] ?? [],
                ];
            }


            // PASS 2: Update Question Linkage and Create all Choices
            foreach ($questions as $qIndex => $q) {
                $questionId = $questionIndexToIdMap[$qIndex];
                $type = $q['type'];

                // 2.1. Update Question Linkage (if dependent dropdown)
                $parentIndex = $q['parent_question_index'] ?? null;
                if ($parentIndex !== null && isset($questionIndexToIdMap[$parentIndex])) {
                    // Update the question with its parent's database ID
                    Question::where('id', $questionId)->update([
                        'parent_question_id' => $questionIndexToIdMap[$parentIndex]
                    ]);
                }

                // 2.2. Create Choices
                if (in_array($type, ['single', 'multiple', 'dropdown']) && !empty($q['choices'])) {
                    foreach ($q['choices'] as $cIndex => $c) {
                        $choice = Choice::create([
                            'question_id' => $questionId,
                            'choice_text' => $c['choice_text'] ?? null,
                            'ordered' => $c['ordered'] ?? $cIndex,
                            'parent_choice_id' => null, // Will be updated in Pass 3
                        ]);

                        // Store the choice ID in the main choice data array for reference in Pass 3
                        $questionChoiceData[$qIndex]['choices'][$cIndex]['choice_id'] = $choice->id;
                    }
                }
            }


            // PASS 3: Update Choice Linkage (for dependent dropdown choices)
            foreach ($questionChoiceData as $qIndex => $data) {
                $choices = $data['choices'];
                foreach ($choices as $cIndex => $c) {
                    $parentChoiceIndex = $c['parent_choice_index'] ?? null;
                    $parentQuestionIndex = $c['parent_question_index'] ?? null;

                    // Check if this choice is dependent (it has parent indices from the form)
                    if ($parentQuestionIndex !== null && $parentChoiceIndex !== null) {

                        // Get the parent question's choice data array
                        $parentChoiceDataArray = $questionChoiceData[$parentQuestionIndex]['choices'] ?? null;

                        // Locate the parent choice's newly created ID using the temporary index
                        if ($parentChoiceDataArray && isset($parentChoiceDataArray[$parentChoiceIndex]['choice_id'])) {
                            $parentChoiceId = $parentChoiceDataArray[$parentChoiceIndex]['choice_id'];

                            // Update the choice with its parent's database ID
                            Choice::where('id', $c['choice_id'])->update([
                                'parent_choice_id' => $parentChoiceId
                            ]);
                        }
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
            'answers',
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

    public function publicTake(string $hash)
    {
        // 1. Find the questionnaire by hash and check if it's set to 'open_for_all'
        $questionnaire = Questionnaire::where('public_hash', $hash)
            ->where('target_response', 'open_for_all')
            ->firstOrFail();

        // 2. Call the core processing logic (see next step)
        return $this->processTakeRequest($questionnaire, null);
    }

    public function take(Questionnaire $questionnaire)
    {
        $user = auth()->user();

        // Enforce permissions for the authenticated route
        if ($questionnaire->target_response !== 'registerd_only') {
            // Redirect registered users to the public URL if it's open for all
            if ($questionnaire->is_open_for_all) {
                return redirect()->route('questionnaire.public_take', $questionnaire->public_hash);
            }
            // Or just abort if the settings are wrong
            return abort(404);
        }

        // Call the core processing logic
        return $this->processTakeRequest($questionnaire, $user);
    }

    private function processTakeRequest(Questionnaire $questionnaire, ?User $user = null)
    {
        if (!$questionnaire->is_active) {
            return redirect()->back()->with('error', 'هذه الاستمارة معطلة.');
        }

        // Check if the user is authenticated AND has already answered (only relevant for registered users)
        if ($user) {
            $answered = Answer::where('questionnaire_id', $questionnaire->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($answered) {
                return redirect()->back()->with('error', 'لقد قمت بالإجابة على هذا الاستبيان مسبقاً.');
            }
        }

        // The main difference in the view will be whether you include user_id in the form submission.
        // The form submission logic must be updated to handle this as well.

        $questionnaire->load(['questions.choices' => function ($query) {
            $query->orderBy('ordered');
        }]);

        // Pass the user state to the view
        return view('questionnaire.take', compact('questionnaire', 'user'));
    }

    // New method to handle public submission via hash
    public function publicSubmit(Request $request, string $hash)
    {
        $questionnaire = Questionnaire::where('public_hash', $hash)
            ->where('target_response', 'open_for_all')
            ->firstOrFail();

        // Pass null for $user since it's a public submission
        return $this->processSubmission($request, $questionnaire, null);
    }

    // Rename your existing submit method to handle the authenticated path
    public function submit(Request $request, Questionnaire $questionnaire)
    {
        $user = auth()->user();

        // Pass the authenticated user
        return $this->processSubmission($request, $questionnaire, $user);
    }

    private function processSubmission(Request $request, Questionnaire $questionnaire, ?User $user = null)
    {
        // --- 1. Access and Restriction Checks (Unchanged) ---

        $isRegisteredOnly = $questionnaire->target_response === 'registerd_only';

        if ($isRegisteredOnly && !$user) {
            return abort(403, 'يجب عليك تسجيل الدخول للإجابة على هذا الاستبيان.');
        }

        if ($user) {
            $alreadyAnswered = Answer::where('questionnaire_id', $questionnaire->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($alreadyAnswered) {
                return abort(403, 'لقد قمت بالإجابة على هذا الاستبيان مسبقاً.');
            }
        }

        // --- 2. Dynamic Validation Rule Generation (New Logic) ---

        $rules = [];
        $messages = [];

        foreach ($questionnaire->questions as $question) {
            $inputName = "question_{$question->id}";

            // **A. Define Base Rules (Assuming all questions are required by default)**
            // You might need to adjust 'required' based on a field in your 'questions' table.
            $baseRules = ['required'];

            // **B. Define Type-Specific Rules**
            switch ($question->type) {
                case 'text':
                    $rules[$inputName] = array_merge($baseRules, ['string', 'max:5000']);
                    break;

                case 'date':
                    $rules[$inputName] = array_merge($baseRules, ['date']);
                    break;

                case 'range':
                    // Assuming min/max are stored on the question, e.g., $question->min_value
                    $min = $question->min_value ?? 1;
                    $max = $question->max_value ?? 10;
                    $rules[$inputName] = array_merge($baseRules, ['integer', 'min:' . $min, 'max:' . $max]);
                    break;

                case 'single':
                    // Must be an integer and must exist in the question's allowed choices (IDs)
                    $choiceIds = $question->choices->pluck('id')->toArray();
                    $rules[$inputName] = array_merge($baseRules, ['integer', 'in:' . implode(',', $choiceIds)]);
                    break;

                case 'multiple':
                    // Must be an array, and all items in the array must be valid choice IDs
                    $choiceIds = $question->choices->pluck('id')->toArray();
                    $rules[$inputName] = array_merge($baseRules, ['array', 'min:1']);
                    $rules["{$inputName}.*"] = ['integer', 'in:' . implode(',', $choiceIds)];

                    // Add an Arabic message for the main array field
                    $messages["{$inputName}.required"] = "يجب اختيار خيار واحد على الأقل لسؤال '{$question->question_text}'";
                    break;
            }

            // Custom message for general required fields
            if (!isset($messages["{$inputName}.required"])) {
                $messages["{$inputName}.required"] = "الرجاء الإجابة على سؤال '{$question->question_text}'.";
            }
        }

        // --- 3. Execute Validation ---

        // Check for validation failures
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // --- 4. Answer Processing Loop (Using Validated Data) ---

        // Loop through each question again to process the now-validated data
        foreach ($questionnaire->questions as $question) {

            $inputName = "question_{$question->id}";
            $noteName  = "note_{$question->id}";

            // Since validation passed, we only skip if the field *wasn't* required and is empty
            // For simplicity here, we rely on the validator to check 'required'.
            if (!$request->has($inputName) && !in_array('required', $rules[$inputName] ?? [])) {
                continue;
            }

            $answerData = [
                'user_id'          => optional($user)->id,
                'questionnaire_id' => $questionnaire->id,
                'question_id'      => $question->id,
                'note'             => $request->filled($noteName) ? $request->input($noteName) : null,
                'text_answer'      => null,
                'range_value'      => null,
                'choice_ids'       => null,
            ];

            // 5. Handle each question type and assign data
            switch ($question->type) {

                case 'text':
                case 'date':
                    // Use the validated text/date input
                    $answerData['text_answer'] = $request->input($inputName);
                    break;

                case 'range':
                    // Use the validated integer range input
                    $answerData['range_value'] = intval($request->input($inputName));
                    break;

                case 'single':
                    // Single choice: Wrap validated ID in an array
                    $choiceId = $request->input($inputName);
                    $answerData['choice_ids'] = $choiceId ? [$choiceId] : null;
                    break;

                case 'multiple':
                    // Multiple choice: Use validated array of IDs
                    $choiceIds = $request->input($inputName, []);
                    $answerData['choice_ids'] = !empty($choiceIds) ? $choiceIds : null;
                    break;
            }

            // 6. Store the answer record
            Answer::create($answerData);
        }

        // 7. Redirect back
        return redirect()->back()->with('success', 'تم إرسال الإجابات بنجاح');
    }

    public function edit(Questionnaire $questionnaire)
    {
        return view('questionnaire.edit', compact('questionnaire'));
    }


    public function update(Request $request, Questionnaire $questionnaire)
    {
        // 1. Validate all incoming data, including the new target_response
        $data = $request->validate([
            'title' => 'required|string|max:255',
            // 'target_response' is required to determine public/registered access
            'target_response' => 'required|in:open_for_all,registerd_only',
            // 'is_active' validation works for '1' or '0' string values
            'is_active' => 'boolean',
        ]);

        // 2. Hash Management based on target_response

        // Check if the type is set to 'open_for_all' AND a hash doesn't exist yet
        if ($data['target_response'] === 'open_for_all' && !$questionnaire->public_hash) {
            $data['public_hash'] = Str::random(32); // Generate unique public hash
        }
        // If it's set back to 'registerd_only', we must clear the hash
        elseif ($data['target_response'] === 'registerd_only') {
            $data['public_hash'] = null;
        }

        // 3. Update the database record
        $questionnaire->update($data);

        // 4. Redirect
        return redirect()->route('questionnaire.index')->with('success', 'تم تحديث الاستبيان بنجاح.');
    }

    public function question_edit(Questionnaire $questionnaire)
    {
        // 1. Load questions and their choices, eager-loaded for efficiency
        //    We also order them by the 'ordered' column to match the front-end structure
        $questionnaire->load(['questions' => function ($query) {
            $query->orderBy('ordered')->with(['choices' => function ($query) {
                $query->orderBy('ordered');
            }]);
        }]);

        // 2. Prepare the data for Alpine.js (Convert to JSON)
        //    We use json_encode on the collection of questions
        $questionsJson = $questionnaire->questions->toJson();

        // 3. Pass both the Questionnaire model and the JSON string to the view
        return view('questionnaire.question_edit', compact('questionnaire', 'questionsJson'));
    }

    public function answer_index(Questionnaire $questionnaire)
    {
        $questionnaire->load([
            'questions.choices',
        ])->loadCount([
            'questions',
            'answers',
        ]);

        return view('questionnaire.answer_index', compact('questionnaire'));
    }

    public function question_update(Request $request, Questionnaire $questionnaire)
    {
        // return $request->all();
        // Validate basic structure
        $validator = Validator::make($request->all(), [
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:single,multiple,range,text,date',
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
        DB::transaction(function () use ($questionnaire, $questions) {

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
                    'min_value' => $q['min_value'] ?? null,
                    'max_value' => $q['max_value'] ?? null,
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

    public function export(Questionnaire $questionnaire)
    {
        // Generate a friendly file name
        $fileName = 'Responses-' . Str::slug($questionnaire->title) . '-' . now()->format('Ymd') . '.xlsx';

        // Use the Excel facade to download the export
        return Excel::download(new QuestionnaireAnswersExport($questionnaire), $fileName);
    }

    public function statistics(Questionnaire $questionnaire)
    {
        $questionnaire->load(['questions.choices', 'questions.answers', 'answers.user']);

        // Calculate overall statistics
        $totalParticipants = $questionnaire->answers->groupBy('user_id')->count();
        $totalAnswers = $questionnaire->answers_count;
        $completionRate = $questionnaire->questions_count > 0 ?
            ($totalAnswers / ($totalParticipants * $questionnaire->questions_count)) * 100 : 0;

        // Get question statistics
        $questionStats = [];
        foreach ($questionnaire->questions as $question) {
            $questionStats[] = [
                'question' => $question,
                'statistics' => $question->getAnswerStatistics()
            ];
        }

        // Response over time data
        $responseOverTime = $this->getResponseOverTime($questionnaire);

        // Participant demographics (if you have user data)
        $participantStats = $this->getParticipantStats($questionnaire);

        return view('questionnaire.statistics', compact(
            'questionnaire',
            'totalParticipants',
            'totalAnswers',
            'completionRate',
            'questionStats',
            'responseOverTime',
            'participantStats'
        ));
    }

    private function getResponseOverTime($questionnaire)
    {
        $answers = $questionnaire->answers()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $answers->pluck('date'),
            'data' => $answers->pluck('count')
        ];
    }

    private function getParticipantStats($questionnaire)
    {
        // This is a placeholder - implement based on your user model fields
        return [
            'total' => $questionnaire->answers->groupBy('user_id')->count(),
            // Add more demographic data as needed
        ];
    }
}
