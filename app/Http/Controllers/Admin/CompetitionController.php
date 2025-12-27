<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComCompetition;
use App\Models\ComQuestion;
use App\Models\ComOption;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = ComCompetition::withCount(['questions', 'participants'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.competitions.index', compact('competitions'));
    }

    public function create()
    {
        return view('admin.competitions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $competition = ComCompetition::create([
            'title' => $validated['title'],
            'status' => 'closed'
        ]);

        return redirect()
            ->route('admin.competitions.show', $competition)
            ->with('success', 'تم إنشاء المنافسة بنجاح!');
    }

    public function show(ComCompetition $competition)
    {
        $competition->load(['questions.options', 'participants']);

        // Generate QR Code
        $qrCode = QrCodeGenerator::size(300)
            ->generate($competition->join_url);

        return view('admin.competitions.show', compact('competition', 'qrCode'));
    }

    public function edit(ComCompetition $competition)
    {
        return view('admin.competitions.edit', compact('competition'));
    }

    public function update(Request $request, ComCompetition $competition)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $competition->update($validated);

        return redirect()
            ->route('admin.competitions.show', $competition)
            ->with('success', 'تم تحديث المسابقة بنجاح!');
    }

    public function destroy(ComCompetition $competition)
    {
        $competition->delete();

        return redirect()
            ->route('admin.competitions.index')
            ->with('success', 'تم حذف المسابقة بنجاح!');
    }

    public function storeQuestion(Request $request, ComCompetition $competition)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2|max:6',
            'options.*' => 'required|string|max:500',
            'correct_option' => 'required|integer|min:0'
        ]);

        // Ensure correct_option index exists
        if (!isset($validated['options'][$validated['correct_option']])) {
            return back()->withErrors(['correct_option' => 'تم اختيار خيار صحيح غير صالح.']);
        }

        $question = $competition->questions()->create([
            'question_text' => $validated['question_text'],
            'order' => $competition->questions()->max('order') + 1
        ]);

        foreach ($validated['options'] as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => $index == $validated['correct_option']
            ]);
        }

        return redirect()
            ->route('admin.competitions.show', $competition)
            ->with('success', 'تمت إضافة السؤال بنجاح!');
    }

    public function destroyQuestion(ComQuestion $question)
    {
        $competitionId = $question->competition_id;
        $question->delete();

        return redirect()
            ->route('admin.competitions.show', $competitionId)
            ->with('success', 'تم حذف السؤال بنجاح!');
    }

    public function start(ComCompetition $competition)
    {
        if (!$competition->isClosed()) {
            return back()->with('error', 'بدأت المنافسة بالفعل!');
        }

        if ($competition->questions()->count() === 0) {
            return back()->with('error', 'يرجى إضافة سؤال واحد على الأقل قبل البدء.');
        }

        $competition->update(['status' => 'started']);

        return back()->with('success', 'بدأت المسابقة! لا يُسمح بمشاركة متسابقين جدد.');
    }

    public function pushQuestion(ComCompetition $competition, ComQuestion $question)
    {
        if (!$competition->isStarted()) {
            return response()->json(['error' => 'لم تبدأ المنافسة بعد'], 400);
        }

        if ($question->competition_id !== $competition->id) {
            return response()->json(['error' => 'هذا السؤال لا ينتمي إلى هذه المسابقة'], 400);
        }

        // Close previous question
        if ($competition->current_question_id) {
            ComQuestion::where('id', $competition->current_question_id)
                ->update(['is_active' => false]);
        }

        // Activate new question
        $question->activate();

        $competition->update([
            'current_question_id' => $question->id,
            'question_started_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'question' => $question->load('options')
        ]);
    }

    public function closeQuestion(ComCompetition $competition)
    {
        if (!$competition->current_question_id) {
            return response()->json(['error' => 'لا يوجد سؤال نشط'], 400);
        }

        $question = ComQuestion::with(['correctOption', 'answers.participant'])
            ->findOrFail($competition->current_question_id);

        $question->deactivate();

        $competition->update([
            'current_question_id' => null,
            'question_started_at' => null
        ]);

        // Get participants with correct answers
        $correctParticipants = $question->answers()
            ->where('is_correct', true)
            ->with('participant')
            ->get()
            ->pluck('participant');

        return response()->json([
            'success' => true,
            'correct_answer' => $question->correctOption,
            'correct_participants' => $correctParticipants,
            'stats' => [
                'total_answers' => $question->getTotalAnswersCount(),
                'correct_answers' => $question->getCorrectAnswersCount(),
                'incorrect_answers' => $question->getIncorrectAnswersCount()
            ]
        ]);
    }

    public function finish(ComCompetition $competition)
    {
        $competition->update([
            'status' => 'finished',
            'current_question_id' => null,
            'question_started_at' => null
        ]);

        // Deactivate all questions
        $competition->questions()->update(['is_active' => false]);

        return redirect()
            ->route('admin.competitions.results', $competition)
            ->with('success', 'انتهت المسابقة!');
    }

    public function liveData(ComCompetition $competition)
    {
        $currentQuestion = null;
        $timeRemaining = 0;

        if ($competition->current_question_id && $competition->question_started_at) {
            $currentQuestion = $competition->currentQuestion->load('options');
            $timeRemaining = $competition->time_remaining;
        }

        return response()->json([
            'status' => $competition->status,
            'current_question' => $currentQuestion,
            'time_remaining' => $timeRemaining,
            'participants_count' => $competition->participants()->count()
        ]);
    }

    public function results(ComCompetition $competition)
    {
        $stats = [
            'total_questions' => $competition->questions()->count(),
            'total_participants' => $competition->participants()->count()
        ];

        $participants = $competition->participants()
            ->with('answers')
            ->get()
            ->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'score' => $participant->score,
                    'correct' => $participant->correctAnswersCount(),
                    'incorrect' => $participant->incorrectAnswersCount(),
                    'accuracy' => $participant->getAccuracy(),
                    'rank' => $participant->getRank()
                ];
            })
            ->sortByDesc('score')
            ->values();

        $leaderboard = $participants->take(10);

        return view('admin.competitions.results', compact('competition', 'stats', 'participants', 'leaderboard'));
    }
}
