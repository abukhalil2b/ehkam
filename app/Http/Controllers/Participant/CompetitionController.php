<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\ComCompetition;
use App\Models\ComParticipant;
use App\Models\ComAnswer;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function join(string $code)
    {
        $competition = ComCompetition::where('join_code', $code)->firstOrFail();

        // Check if already registered
        $participant = $this->getParticipant($competition);

        if ($participant) {
            if ($competition->isClosed()) {
                return redirect()->route('participant.competition.wait', $competition);
            } elseif ($competition->isStarted()) {
                return redirect()->route('participant.competition.play', $competition);
            } else {
                return view('participant.finished');
            }
        }

        return view('participant.join', compact('competition'));
    }

    public function register(Request $request, string $code)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $competition = ComCompetition::where('join_code', $code)->firstOrFail();

        if (!$competition->canAcceptParticipants()) {
            return back()->with('error', 'بدأت المنافسة بالفعل. لا يُسمح بدخول مشاركين جدد.');
        }

        // Check if IP already registered
        $existing = $competition->participants()
            ->where('ip_address', $request->ip())
            ->first();

        if ($existing) {
            session(['participant_id' => $existing->id]);
            return redirect()->route('participant.competition.wait', $competition);
        }

        $participant = $competition->participants()->create([
            'name' => $validated['name'],
            'ip_address' => $request->ip()
        ]);

        session(['participant_id' => $participant->id]);

        return redirect()->route('participant.competition.wait', $competition);
    }

    public function wait(ComCompetition $competition)
    {
        $participant = $this->getParticipant($competition);

        if (!$participant) {
            return redirect()->route('participant.competition.join', $competition->join_code);
        }

        if ($competition->isStarted()) {
            return redirect()->route('participant.competition.play', $competition);
        }

        return view('participant.wait', compact('competition', 'participant'));
    }

    public function play(ComCompetition $competition)
    {
        $participant = $this->getParticipant($competition);

        if (!$participant) {
            return redirect()->route('participant.competition.join', $competition->join_code);
        }

        // Check if competition is finished
        if ($competition->isFinished()) {
            return redirect()->route('participant.competition.finished', $competition);
        }

        // Check if participant has finished all questions
        if ($participant->isCompetitionFinished()) {
            return redirect()->route('participant.competition.finished', $competition);
        }

        if (!$competition->isStarted()) {
            return redirect()->route('participant.competition.wait', $competition);
        }

        // Load ALL questions with their options for bulk answering
        $questions = $competition->questions()
            ->with('options')
            ->orderBy('order')
            ->get();

        return view('participant.play', compact('competition', 'participant', 'questions'));
    }

    public function finished(ComCompetition $competition)
    {
        $participant = $this->getParticipant($competition);

        if (!$participant) {
            return redirect()->route('participant.competition.join', $competition->join_code);
        }

        return view('participant.finished', compact('competition', 'participant'));
    }

    public function submitAnswer(Request $request, ComCompetition $competition)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:com_questions,id',
            'option_id' => 'required|exists:com_options,id'
        ]);

        $participant = $this->getParticipant($competition);

        if (!$participant) {
            return response()->json(['error' => 'لم يتم العثور على المشارك'], 404);
        }

        // Check if question is still active
        $question = $competition->questions()
            ->where('id', $validated['question_id'])
            ->where('is_active', true)
            ->first();

        if (!$question) {
            return response()->json(['error' => 'السؤال غير نشط أو انتهت صلاحيته'], 400);
        }

        // Check if already answered
        if ($participant->hasAnsweredQuestion($validated['question_id'])) {
            return response()->json(['error' => 'لقد أجبتُ على هذا السؤال مسبقاً'], 400);
        }

        // Verify option belongs to question
        $option = $question->options()->findOrFail($validated['option_id']);

        $answer = ComAnswer::create([
            'participant_id' => $participant->id,
            'question_id' => $validated['question_id'],
            'option_id' => $validated['option_id']
        ]);

        return response()->json([
            'success' => true,
            'is_correct' => $answer->is_correct,
            'score' => $participant->fresh()->score
        ]);
    }

    /**
     * Submit all answers at once (bulk submission)
     */
    public function submitAllAnswers(Request $request, ComCompetition $competition)
    {
        $validated = $request->validate([
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:com_questions,id',
            'answers.*.option_id' => 'required|exists:com_options,id'
        ]);

        $participant = $this->getParticipant($competition);

        if (!$participant) {
            return response()->json(['error' => 'لم يتم العثور على المشارك'], 404);
        }

        $results = [];
        $correctCount = 0;
        $incorrectCount = 0;

        foreach ($validated['answers'] as $answerData) {
            // Check if already answered
            if ($participant->hasAnsweredQuestion($answerData['question_id'])) {
                continue; // Skip already answered questions
            }

            // Verify the question belongs to this competition
            $question = $competition->questions()->find($answerData['question_id']);
            if (!$question) {
                continue;
            }

            // Verify option belongs to question
            $option = $question->options()->find($answerData['option_id']);
            if (!$option) {
                continue;
            }

            // Create answer
            $answer = ComAnswer::create([
                'participant_id' => $participant->id,
                'question_id' => $answerData['question_id'],
                'option_id' => $answerData['option_id']
            ]);

            $results[] = [
                'question_id' => $answerData['question_id'],
                'is_correct' => $answer->is_correct
            ];

            if ($answer->is_correct) {
                $correctCount++;
            } else {
                $incorrectCount++;
            }
        }

        // Refresh participant to get updated score
        $participant->refresh();

        return response()->json([
            'success' => true,
            'results' => $results,
            'correct_count' => $correctCount,
            'incorrect_count' => $incorrectCount,
            'final_score' => $participant->score
        ]);
    }

    public function liveData(ComCompetition $competition)
    {
        $participant = $this->getParticipant($competition);

        if (!$participant) {
            return response()->json(['error' => 'Not registered'], 401);
        }

        // Check if participant has finished all their questions
        if ($participant->isCompetitionFinished()) {
            return response()->json([
                'status' => 'finished',
                'current_question' => null,
                'has_answered' => true,
                'time_remaining' => 0,
                'show_results' => true,
                'score' => $participant->score,
                'competition_finished' => true,
                'participant_finished' => true
            ]);
        }

        // Check if competition is finished
        if ($competition->isFinished()) {
            return response()->json([
                'status' => 'finished',
                'current_question' => null,
                'has_answered' => true,
                'time_remaining' => 0,
                'show_results' => true,
                'score' => $participant->score,
                'competition_finished' => true,
                'participant_finished' => false
            ]);
        }

        // Auto-sequential mode
        if ($participant->auto_mode && $participant->current_question_id && $participant->question_started_at) {
            $currentQuestion = $participant->currentQuestion->load('options');
            $timeRemaining = $participant->getTimeRemainingAttribute();
            $hasAnswered = $participant->hasAnsweredQuestion($currentQuestion->id);

            // Check if time expired and advance to next question
            if ($timeRemaining <= 0 && !$hasAnswered) {
                // Time's up for this question, advance to next
                $nextQuestion = $participant->advanceToNextQuestion();

                if ($nextQuestion) {
                    $currentQuestion = $nextQuestion->load('options');
                    $hasAnswered = false;
                    $timeRemaining = 30;
                } else {
                    // No more questions, participant finished
                    return response()->json([
                        'status' => 'started',
                        'current_question' => null,
                        'has_answered' => true,
                        'time_remaining' => 0,
                        'show_results' => true,
                        'score' => $participant->score,
                        'competition_finished' => false,
                        'participant_finished' => true
                    ]);
                }
            }

            return response()->json([
                'status' => $competition->status,
                'current_question' => $currentQuestion,
                'has_answered' => $hasAnswered,
                'time_remaining' => $timeRemaining,
                'show_results' => false,
                'score' => $participant->score,
                'auto_mode' => true,
                'competition_finished' => false,
                'participant_finished' => false
            ]);
        }

        // Manual mode (original behavior) - also handles auto_mode participants who haven't been initialized
        $currentQuestion = null;
        $hasAnswered = false;
        $timeRemaining = 0;
        $showResults = false;

        if ($competition->current_question_id && $competition->question_started_at) {
            $currentQuestion = $competition->currentQuestion->load('options');
            $timeRemaining = $competition->time_remaining;

            // Check if participant answered
            $hasAnswered = $participant->hasAnsweredQuestion($currentQuestion->id);
        } else {
            // Question closed, show last question results
            $lastQuestion = $competition->questions()
                ->where('is_active', false)
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($lastQuestion) {
                // Only show results if participant was present when question closed or has answered it
                if ($participant->created_at->lte($lastQuestion->updated_at) || $participant->hasAnsweredQuestion($lastQuestion->id)) {
                    $showResults = true;
                }
            }
        }

        return response()->json([
            'status' => $competition->status,
            'current_question' => $currentQuestion,
            'has_answered' => $hasAnswered,
            'time_remaining' => $timeRemaining,
            'show_results' => $showResults,
            'score' => $participant->score,
            'auto_mode' => $participant->auto_mode || false,
            'competition_finished' => false,
            'participant_finished' => false
        ]);
    }

    private function getParticipant(ComCompetition $competition)
    {
        $participantId = session('participant_id');

        if ($participantId) {
            return $competition->participants()->find($participantId);
        }

        // Fallback to IP address
        return $competition->participants()
            ->where('ip_address', request()->ip())
            ->first();
    }
}
