<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use App\Models\UserPositionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AssessmentResultController extends Controller
{
    /**
     * Show the form for creating a new set of assessment results for a specific activity.
     */
    public function create(Activity $activity)
    {
        // 1. DUPLICATE SUBMISSION CHECK
        // If the user has already submitted results for this activity, redirect them to the edit form.
        $hasSubmitted = AssessmentResult::where('activity_id', $activity->id)
            ->exists();

        if ($hasSubmitted) {
            return redirect()->route('assessment_result.edit', $activity->id)
                ->with('warning', 'لقد تم تقييم هذا النشاط مسبقاً. يمكنك تعديل التقييم الحالي.');
        }

        $questions = AssessmentQuestion::orderBy('ordered')->get();
        return view('assessment_result.create', compact('activity', 'questions'));
    }

    /**
     * Show the form for editing the assessment results for a specific activity.
     */
    public function edit(Activity $activity)
    {
        // Check if the user has submitted results (must exist to edit)
        $userResults = AssessmentResult::where('activity_id', $activity->id)
            ->with('assessmentQuestion')
            ->get()
            ->keyBy('assessment_question_id');

        if ($userResults->isEmpty()) {
            return redirect()->route('assessment_result.create', $activity->id)
                ->with('error', 'لم تقم بتقديم تقييم لهذا النشاط بعد. يرجى البدء بتقييم جديد.');
        }

        $allQuestions = AssessmentQuestion::orderBy('ordered')->get();

        // Pass both questions and the user's existing results to the view
        return view('assessment_result.edit', compact('activity', 'allQuestions', 'userResults'));
    }

    public function store(Request $request)
    {
        return $this->saveAssessmentResults($request, null); // null = new submission
    }

    public function update(Request $request, Activity $activity)
    {
        // return $request->all();
        return $this->saveAssessmentResults($request, $activity->id); // existing activity
    }

    /**
     * Save or update assessment results for a user & activity.
     * If activityId is null, it comes from request (store), else use given (update)
     */
    private function saveAssessmentResults(Request $request, $activityId = null)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
        ]);

        $userId = Auth::id();
        $position = UserPositionHistory::where('user_id',$userId)->latest('created_at')->first();
        $positionId = $position ? $position->position_id : null;
        $activityId = $activityId ?? $request->input('activity_id');
        $currentTime = now();
        $assessmentYear = date('Y');

        // Fetch questions for current year
        $questions = AssessmentQuestion::where('assessment_year', $assessmentYear)->get();

        // Delete previous results for this user/activity/year
        AssessmentResult::where('activity_id', $activityId)
            ->whereHas('assessmentQuestion', fn($q) => $q->where('assessment_year', $assessmentYear))
            ->delete();

        $resultsToCreate = [];
        $hasAnyData = false;

        foreach ($questions as $question) {
            $answerKey = 'question_' . $question->id;
            $noteKey   = 'note_' . $question->id;

            $answerValue = $request->input($answerKey);
            $noteValue   = $request->input($noteKey);

            $rangeAnswer = null;
            $textAnswer  = null;

            if ($question->type === 'range' && is_numeric($answerValue) && $answerValue >= 1 && $answerValue <= $question->max_point) {
                $rangeAnswer = (int)$answerValue;
                $hasAnyData = true;
            } elseif ($question->type === 'text' && !empty($answerValue)) {
                $textAnswer = (string)$answerValue;
                $hasAnyData = true;
            }

            if (!empty($noteValue)) {
                $hasAnyData = true;
            }

            if ($rangeAnswer !== null || $textAnswer !== null || !empty($noteValue)) {
                $resultsToCreate[] = [
                    'activity_id' => $activityId,
                    'assessment_question_id' => $question->id,
                    'user_id' => $userId,
                    'position_id'=>$positionId,
                    'range_answer' => $rangeAnswer,
                    'text_answer' => $textAnswer,
                    'note' => $noteValue,
                    'assessment_year' => $assessmentYear,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
            }
        }

        if (!$hasAnyData) {
            return back()->with('error', 'يجب تقديم إجابة أو ملحوظة واحدة على الأقل لأي سؤال.')->withInput();
        }

        AssessmentResult::insert($resultsToCreate);

        return redirect()->route('activity.show', $activityId)
            ->with('success', 'تم تسجيل نتائج التقييم بنجاح!');
    }
}
