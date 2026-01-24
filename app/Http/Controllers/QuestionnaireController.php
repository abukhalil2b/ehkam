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
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Exports\QuestionnaireAnswersExport;
use Maatwebsite\Excel\Facades\Excel;

class QuestionnaireController extends Controller
{
    // ==========================
    // 1. BASIC CRUD & INDEX
    // ==========================

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
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'is_active' => 'boolean',
            'target_response' => 'required|in:open_for_all,registerd_only',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => ['required', Rule::in(['single', 'multiple', 'range', 'text', 'date', 'dropdown'])],

            // Allow empty choices in basic validation, caught by logic below
            'questions.*.choices' => 'nullable|array',
            'questions.*.choices.*.choice_text' => 'nullable|string',
            'questions.*.choices.*.parent_choice_index' => 'nullable|integer',

            'questions.*.min_value' => 'exclude_unless:questions.*.type,range|required|integer',
            'questions.*.max_value' => 'exclude_unless:questions.*.type,range|required|integer|gte:questions.*.min_value',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $questionsInput = $request->input('questions', []);

        // 2. Logic Validation (Ensure non-empty choices)
        foreach ($questionsInput as $i => $q) {
            if (in_array($q['type'], ['single', 'multiple', 'dropdown'])) {
                if (empty($q['choices'])) {
                    return redirect()->back()
                        ->withErrors(["questions.$i.choices" => "يجب إضافة خيارات للسؤال رقم " . ($i + 1)])
                        ->withInput();
                }
                foreach ($q['choices'] as $j => $c) {
                    if (empty(trim($c['choice_text'] ?? ''))) {
                        return redirect()->back()
                            ->withErrors(["questions.$i.choices.$j" => "نص الخيار مطلوب في السؤال رقم " . ($i + 1)])
                            ->withInput();
                    }
                }
            }
        }

        // 3. Save to DB (Three-Pass Logic)
        DB::transaction(function () use ($request, $questionsInput) {
            $questionnaire = Questionnaire::create([
                'title' => $request->title,
                'is_active' => $request->boolean('is_active'),
                'target_response' => $request->target_response,
                'public_hash' => $request->target_response === 'open_for_all' ? Str::random(32) : null,
            ]);

            $qIndexToIdMap = [];
            $qChoiceData = [];

            // Pass 1: Questions
            foreach ($questionsInput as $index => $q) {
                $question = Question::create([
                    'questionnaire_id' => $questionnaire->id,
                    'type' => $q['type'],
                    'question_text' => $q['question_text'],
                    'min_value' => $q['min_value'] ?? null,
                    'max_value' => $q['max_value'] ?? null,
                    'ordered' => $index,
                ]);
                $qIndexToIdMap[$index] = $question->id;
                $qChoiceData[$index] = $q['choices'] ?? [];
            }

            // Pass 2: Links & Choices
            foreach ($questionsInput as $qIndex => $q) {
                $questionId = $qIndexToIdMap[$qIndex];

                $parentQIdx = $q['parent_question_index'] ?? null;
                if (!is_null($parentQIdx) && isset($qIndexToIdMap[$parentQIdx])) {
                    Question::where('id', $questionId)->update([
                        'parent_question_id' => $qIndexToIdMap[$parentQIdx]
                    ]);
                }

                if (!empty($qChoiceData[$qIndex])) {
                    $choices = array_values($qChoiceData[$qIndex]); // Ensure sequential keys
                    foreach ($choices as $cIndex => $cData) {
                        $choice = Choice::create([
                            'question_id' => $questionId,
                            'choice_text' => $cData['choice_text'],
                            'ordered' => $cIndex,
                        ]);
                        $qChoiceData[$qIndex][$cIndex]['db_id'] = $choice->id;
                    }
                }
            }

            // Pass 3: Dependent Choices
            foreach ($questionsInput as $qIndex => $q) {
                $parentQIdx = $q['parent_question_index'] ?? null;
                if (!is_null($parentQIdx) && isset($qChoiceData[$parentQIdx])) {
                    $choices = array_values($qChoiceData[$qIndex]);
                    foreach ($choices as $cData) {
                        $parentChoiceIdx = $cData['parent_choice_index'] ?? null;
                        if (!is_null($parentChoiceIdx) && isset($qChoiceData[$parentQIdx][$parentChoiceIdx]['db_id'])) {
                            Choice::where('id', $cData['db_id'])->update([
                                'parent_choice_id' => $qChoiceData[$parentQIdx][$parentChoiceIdx]['db_id']
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
        $questionnaire->load(['questions.choices'])->loadCount(['questions', 'answers']);
        return view('questionnaire.show', compact('questionnaire'));
    }

    public function edit(Questionnaire $questionnaire)
    {
        return view('questionnaire.edit', compact('questionnaire'));
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'target_response' => 'required|in:open_for_all,registerd_only',
            'is_active' => 'boolean',
        ]);

        if ($data['target_response'] === 'open_for_all' && !$questionnaire->public_hash) {
            $data['public_hash'] = Str::random(32);
        } elseif ($data['target_response'] === 'registerd_only') {
            $data['public_hash'] = null;
        }

        $questionnaire->update($data);
        return redirect()->route('questionnaire.index')->with('success', 'تم تحديث الاستبيان بنجاح.');
    }

    public function delete(Questionnaire $questionnaire)
    {
        $questionnaire->delete();
        return redirect()->route('questionnaire.index')->with('success', 'تم حذف الاستبيان بنجاح.');
    }

    public function duplicate(Questionnaire $questionnaire)
    {
        $copy = $questionnaire->replicate();
        $copy->title = $questionnaire->title . ' (نسخة)';
        $copy->is_active = false;
        $copy->push();

        foreach ($questionnaire->questions as $question) {
            $newQuestion = $question->replicate();
            $newQuestion->questionnaire_id = $copy->id;
            $newQuestion->push();

            // Note: Deep duplication of choices and dependent links requires more complex logic.
            // This basic replication copies questions but might lose dependent structure if not handled carefully.
            // For simple questionnaires, this is fine. For dependent ones, consider a more robust deep copy.
            foreach ($question->choices as $choice) {
                $newChoice = $choice->replicate();
                $newChoice->question_id = $newQuestion->id;
                $newChoice->save();
            }
        }

        return redirect()->route('questionnaire.index')->with('success', 'تم إنشاء نسخة من الاستبيان بنجاح.');
    }

    // ==========================
    // 2. STRUCTURE EDITING
    // ==========================

    public function question_edit(Questionnaire $questionnaire)
    {
        if ($questionnaire->answers()->exists()) {
            return redirect()->route('questionnaire.index')->with('error', 'لا يمكن تعديل الأسئلة لأن هناك إجابات مسجلة عليها.');
        }

        $questionnaire->load(['questions.choices' => function ($q) {
            $q->orderBy('ordered');
        }]);
        $questionsJson = $questionnaire->questions->toJson();

        return view('questionnaire.question_edit', compact('questionnaire', 'questionsJson'));
    }

    public function question_update(Request $request, Questionnaire $questionnaire)
    {
        if ($questionnaire->answers()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن تعديل بنية الأسئلة لوجود إجابات مسجلة.');
        }

        // Reuse validation logic from store()
        $validator = Validator::make($request->all(), [
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => ['required', Rule::in(['single', 'multiple', 'range', 'text', 'date', 'dropdown'])],
            'questions.*.choices' => 'nullable|array',
            'questions.*.choices.*.choice_text' => 'nullable|string',
            'questions.*.min_value' => 'exclude_unless:questions.*.type,range|required|integer',
            'questions.*.max_value' => 'exclude_unless:questions.*.type,range|required|integer|gte:questions.*.min_value',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $questionsInput = $request->input('questions', []);

        DB::transaction(function () use ($questionnaire, $questionsInput) {
            foreach ($questionnaire->questions as $q) {
                $q->choices()->delete();
            }
            $questionnaire->questions()->delete();

            // Re-run the store() logic
            $qIndexToIdMap = [];
            $qChoiceData = [];

            foreach ($questionsInput as $index => $q) {
                $question = Question::create([
                    'questionnaire_id' => $questionnaire->id,
                    'type' => $q['type'],
                    'question_text' => $q['question_text'],
                    'min_value' => $q['min_value'] ?? null,
                    'max_value' => $q['max_value'] ?? null,
                    'ordered' => $index,
                ]);
                $qIndexToIdMap[$index] = $question->id;
                $qChoiceData[$index] = $q['choices'] ?? [];
            }

            foreach ($questionsInput as $qIndex => $q) {
                $questionId = $qIndexToIdMap[$qIndex];
                $parentQIdx = $q['parent_question_index'] ?? null;
                if (!is_null($parentQIdx) && isset($qIndexToIdMap[$parentQIdx])) {
                    Question::where('id', $questionId)->update(['parent_question_id' => $qIndexToIdMap[$parentQIdx]]);
                }
                if (!empty($qChoiceData[$qIndex])) {
                    $choices = array_values($qChoiceData[$qIndex]);
                    foreach ($choices as $cIndex => $cData) {
                        $choice = Choice::create([
                            'question_id' => $questionId,
                            'choice_text' => $cData['choice_text'],
                            'ordered' => $cIndex,
                        ]);
                        $qChoiceData[$qIndex][$cIndex]['db_id'] = $choice->id;
                    }
                }
            }

            foreach ($questionsInput as $qIndex => $q) {
                $parentQIdx = $q['parent_question_index'] ?? null;
                if (!is_null($parentQIdx) && isset($qChoiceData[$parentQIdx])) {
                    $choices = array_values($qChoiceData[$qIndex]);
                    foreach ($choices as $cData) {
                        $parentChoiceIdx = $cData['parent_choice_index'] ?? null;
                        if (!is_null($parentChoiceIdx) && isset($qChoiceData[$parentQIdx][$parentChoiceIdx]['db_id'])) {
                            Choice::where('id', $cData['db_id'])->update([
                                'parent_choice_id' => $qChoiceData[$parentQIdx][$parentChoiceIdx]['db_id']
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('questionnaire.index')->with('success', 'تم تحديث الاستبيان بنجاح.');
    }

    // ==========================
    // 3. SHARING & TAKING
    // ==========================

    public function shareLink(Questionnaire $questionnaire)
    {
        $accessUrl = null;
        $qrCode = null;

        if ($questionnaire->target_response === 'open_for_all' && $questionnaire->public_hash) {
            $accessUrl = route('questionnaire.public_take', $questionnaire->public_hash);
        } elseif ($questionnaire->target_response === 'registerd_only') {
            $accessUrl = route('questionnaire.registered_take', $questionnaire);
        }

        if ($accessUrl && $questionnaire->is_active) {
            $qrCode = QrCode::size(300)->generate($accessUrl);
        }

        return view('questionnaire.share_link', compact('questionnaire', 'accessUrl', 'qrCode'));
    }

    public function publicTake(string $hash)
    {
        $questionnaire = Questionnaire::where('public_hash', $hash)
            ->where('target_response', 'open_for_all')
            ->firstOrFail();

        if (!$questionnaire->is_active) {
            abort(403, 'هذه الاستمارة معطلة حالياً.');
        }

        $questionnaire->load(['questions.choices' => function ($query) {
            $query->orderBy('ordered');
        }]);

        $qrImage = QrCode::size(200)->generate(route('questionnaire.public_take', $hash));

        return view('questionnaire.take', compact('questionnaire', 'qrImage'));
    }

    public function registeredTake(Questionnaire $questionnaire)
    {
        $user = auth()->user();

        if ($questionnaire->target_response !== 'registerd_only') {
            if ($questionnaire->public_hash) {
                return redirect()->route('questionnaire.public_take', $questionnaire->public_hash);
            }
            abort(404);
        }

        if (!$questionnaire->is_active) {
            return redirect()->back()->with('error', 'هذه الاستمارة معطلة.');
        }

        if ($user) {
            if (Answer::where('questionnaire_id', $questionnaire->id)->where('user_id', $user->id)->exists()) {
                return redirect()->back()->with('error', 'لقد قمت بالإجابة على هذا الاستبيان مسبقاً.');
            }
        }

        $questionnaire->load(['questions.choices' => function ($query) {
            $query->orderBy('ordered');
        }]);

        return view('questionnaire.take', compact('questionnaire', 'user'));
    }

    public function publicSubmit(Request $request, string $hash)
    {
        $questionnaire = Questionnaire::where('public_hash', $hash)
            ->where('target_response', 'open_for_all')
            ->firstOrFail();

        return $this->processSubmission($request, $questionnaire, null);
    }

    public function submit(Request $request, Questionnaire $questionnaire)
    {
        $user = auth()->user();
        return $this->processSubmission($request, $questionnaire, $user);
    }

    private function processSubmission(Request $request, Questionnaire $questionnaire, ?User $user = null)
    {
        if ($questionnaire->target_response === 'registerd_only' && !$user) {
            abort(403, 'يجب عليك تسجيل الدخول.');
        }

        if ($user && Answer::where('questionnaire_id', $questionnaire->id)->where('user_id', $user->id)->exists()) {
            abort(403, 'تمت الإجابة مسبقاً.');
        }

        $rules = [];
        $messages = [];

        foreach ($questionnaire->questions as $question) {
            $inputName = "question_{$question->id}";
            $baseRules = ['required'];

            switch ($question->type) {
                case 'text':
                    $rules[$inputName] = array_merge($baseRules, ['string', 'max:5000']);
                    break;
                case 'date':
                    $rules[$inputName] = array_merge($baseRules, ['date']);
                    break;
                case 'range':
                    $min = $question->min_value ?? 1;
                    $max = $question->max_value ?? 10;
                    $rules[$inputName] = array_merge($baseRules, ['integer', "min:$min", "max:$max"]);
                    break;
                case 'single':
                case 'dropdown':
                    $choiceIds = $question->choices->pluck('id')->toArray();
                    $rules[$inputName] = array_merge($baseRules, ['integer', 'in:' . implode(',', $choiceIds)]);
                    break;
                case 'multiple':
                    $choiceIds = $question->choices->pluck('id')->toArray();
                    $rules[$inputName] = array_merge($baseRules, ['array', 'min:1']);
                    $rules["{$inputName}.*"] = ['integer', 'in:' . implode(',', $choiceIds)];
                    break;
            }
            $messages["{$inputName}.required"] = "السؤال '{$question->question_text}' مطلوب.";
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($request, $questionnaire, $user) {
            foreach ($questionnaire->questions as $question) {
                $inputName = "question_{$question->id}";
                $noteName  = "note_{$question->id}";

                if (!$request->has($inputName)) continue;

                $answerData = [
                    'user_id'          => optional($user)->id,
                    'questionnaire_id' => $questionnaire->id,
                    'question_id'      => $question->id,
                    'note'             => $request->input($noteName),
                    'text_answer'      => null,
                    'range_value'      => null,
                    'choice_ids'       => null,
                ];

                switch ($question->type) {
                    case 'text':
                    case 'date':
                        $answerData['text_answer'] = $request->input($inputName);
                        break;
                    case 'range':
                        $answerData['range_value'] = intval($request->input($inputName));
                        break;
                    case 'single':
                    case 'dropdown':
                        $val = $request->input($inputName);
                        $answerData['choice_ids'] = [$val];
                        break;
                    case 'multiple':
                        $answerData['choice_ids'] = $request->input($inputName);
                        break;
                }
                Answer::create($answerData);
            }
        });

        return view('questionnaire.thank_you_for_answer')->with('success', 'شكراً لك! تم استلام إجاباتك بنجاح.');
    }

    // ==========================
    // 4. ADMIN: ANSWERS & STATS
    // ==========================

    public function answer_index(Questionnaire $questionnaire)
    {
        $questionnaire->load(['questions.choices', 'answers.user', 'answers.question'])->loadCount(['questions', 'answers']);
        return view('questionnaire.answer_index', compact('questionnaire'));
    }

    public function answerShow(Answer $answer)
    {
        $user = auth()->user();
        if ($user->id !== $answer->questionnaire->created_by && $user->id != 1) abort(403);

        $answer->load(['question', 'questionnaire', 'user']);
        return view('questionnaire.answer_show', compact('answer'));
    }

    public function updateAnswer(Request $request, Answer $answer)
    {
        $user = auth()->user();
        if ($user->id !== $answer->questionnaire->created_by && $user->id != 1) abort(403);

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
            case 'dropdown':
                $input = $request->input('choice_ids');
                $val = is_array($input) ? ($input[0] ?? null) : $input;
                $data['choice_ids'] = $val ? [$val] : null;
                break;
            case 'multiple':
                $data['choice_ids'] = $request->input('choice_ids', []);
                break;
        }

        $answer->update($data);
        return redirect()->route('questionnaire.answer_index', $answer->questionnaire_id)->with('success', 'تم تحديث الإجابة بنجاح');
    }

    public function destroyAnswer(Answer $answer)
    {
        $user = auth()->user();
        if ($user->id !== $answer->questionnaire->created_by && $user->id != 1) abort(403);

        $qId = $answer->questionnaire_id;
        $answer->delete();
        return redirect()->route('questionnaire.answer_index', $qId)->with('success', 'تم حذف الإجابة بنجاح');
    }

    public function export(Questionnaire $questionnaire)
    {
        $fileName = 'Responses-' . Str::slug(Str::substr($questionnaire->title, 0, 20)) . '-' . now()->format('Ymd') . '.xlsx';
        return Excel::download(new QuestionnaireAnswersExport($questionnaire), $fileName);
    }

    // --- RESTORED METHODS: STATISTICS & PUBLIC RESULTS ---

    public function showPublicResults(Questionnaire $questionnaire)
    {
        if ($questionnaire->target_response !== 'open_for_all') {
            abort(403, 'هذا رابط عام');
        }

        $questionnaire->load(['questions.choices']);

        // Count unique submissions by grouping created_at timestamp
        $totalResponses = $questionnaire->answers()
            ->selectRaw('count(DISTINCT created_at) as count')
            ->first()
            ->count ?? 0;

        $results = $this->calculateQuestionStats($questionnaire);

        return view('questionnaire.public_result', compact('questionnaire', 'results', 'totalResponses'));
    }

    private function calculateQuestionStats(Questionnaire $questionnaire)
    {
        $results = [];

        foreach ($questionnaire->questions as $question) {
            $answers = $question->answers;
            $stats = [
                'type' => $question->type,
                'total_answers' => $answers->count(),
                'breakdown' => [],
            ];

            switch ($question->type) {
                case 'single':
                case 'dropdown':
                case 'multiple':
                    // Flatten choice_ids (handling both array casting and JSON strings if needed)
                    $allChoiceIds = $answers->pluck('choice_ids')
                        ->flatten()
                        ->filter();

                    $choiceCounts = $allChoiceIds->countBy();

                    foreach ($question->choices as $choice) {
                        $count = $choiceCounts->get($choice->id, 0);
                        $stats['breakdown'][] = [
                            'text' => $choice->choice_text,
                            'count' => $count,
                            'percentage' => $stats['total_answers'] > 0 ? round(($count / $stats['total_answers']) * 100, 1) : 0,
                        ];
                    }
                    break;

                case 'range':
                    $values = $answers->pluck('range_value')->filter();
                    if ($values->isNotEmpty()) {
                        $stats['breakdown']['average'] = round($values->avg(), 2);
                        $stats['breakdown']['min'] = $values->min();
                        $stats['breakdown']['max'] = $values->max();
                        $stats['breakdown']['distribution'] = $values->countBy()->sortKeys();
                    }
                    break;

                case 'text':
                case 'date':
                    $stats['breakdown'] = $answers->pluck('text_answer')->filter()->take(50);
                    break;
            }

            $results[$question->id] = $stats;
        }

        return $results;
    }
    public function statistics(Questionnaire $questionnaire)
    {
        // 1. Eager load necessary relationships
        $questionnaire->load(['questions.choices', 'questions.answers', 'answers']);

        // 2. General Stats
        $totalParticipants = $questionnaire->answers->groupBy('user_id')->count();
        $totalAnswers = $questionnaire->answers->count(); // Count total answer rows

        // Calculate Completion Rate (approximate based on answer count vs expected)
        $completionRate = 0;
        if ($totalParticipants > 0 && $questionnaire->questions_count > 0) {
            $completionRate = ($totalAnswers / ($totalParticipants * $questionnaire->questions_count)) * 100;
            $completionRate = min(100, $completionRate); // Cap at 100%
        }

        // 3. Question-Specific Stats
        $questionStats = [];
        foreach ($questionnaire->questions as $question) {
            $statData = [
                'total_responses' => $question->answers->count(),
                'type' => $question->type,
                'chart_data' => null // Will hold data for Chart.js
            ];

            // Logic to build Chart.js compatible data
            if (in_array($question->type, ['single', 'multiple', 'dropdown'])) {
                // Flatten answers to get all choice IDs
                $allChoiceIds = $question->answers->pluck('choice_ids')
                    ->flatten()
                    ->filter();

                $counts = $allChoiceIds->countBy();

                $labels = [];
                $data = [];

                foreach ($question->choices as $choice) {
                    $labels[] = $choice->choice_text;
                    $data[] = $counts->get($choice->id, 0);
                }

                $statData['chart_data'] = [
                    'labels' => $labels,
                    'data' => $data,
                ];
            } elseif ($question->type === 'range') {
                // Group by value (1, 2, 3, 4, 5...)
                $values = $question->answers->pluck('range_value')->filter();
                $distribution = $values->countBy();

                // Ensure all steps exist in chart even if count is 0
                $min = $question->min_value ?? 1;
                $max = $question->max_value ?? 5;
                $labels = range($min, $max);
                $data = [];

                foreach ($labels as $val) {
                    $data[] = $distribution->get($val, 0);
                }

                $statData['chart_data'] = [
                    'labels' => $labels,
                    'data' => $data,
                    'average' => $values->isNotEmpty() ? round($values->avg(), 1) : 0
                ];
            } elseif (in_array($question->type, ['text', 'date'])) {
                // Just take the latest 5 text answers
                $statData['latest_answers'] = $question->answers()
                    ->latest()
                    ->take(5)
                    ->pluck('text_answer')
                    ->filter()
                    ->toArray();
            }

            $questionStats[] = [
                'question' => $question,
                'statistics' => $statData
            ];
        }

        // 4. Response Over Time (Line Chart)
        $responseOverTime = $this->getResponseOverTime($questionnaire);

        return view('questionnaire.statistics', compact(
            'questionnaire',
            'totalParticipants',
            'totalAnswers',
            'completionRate',
            'questionStats',
            'responseOverTime'
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
        return [
            'total' => $questionnaire->answers->groupBy('user_id')->count(),
        ];
    }
}
