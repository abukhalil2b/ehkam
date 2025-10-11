<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
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
                                        ->where('user_id', Auth::id())
                                        ->exists();

        if ($hasSubmitted) {
            return redirect()->route('assessment_result.edit', $activity->id)
                             ->with('warning', 'لقد قمت بتقييم هذا النشاط مسبقاً. يمكنك تعديل تقييمك الحالي.');
        }
        
        $questions = AssessmentQuestion::orderBy('ordered')->get();
        return view('assessment_result.create', compact('activity', 'questions'));
    }

    /**
     * Store the assessment results submitted from the form.
     */
    public function store(Request $request)
    {
        // 1. Validate activity ID
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
        ]);
        
        $activityId = $request->input('activity_id');
        
        // 2. REPEAT DUPLICATE SUBMISSION CHECK
        if (AssessmentResult::where('activity_id', $activityId)->where('user_id', Auth::id())->exists()) {
             throw ValidationException::withMessages(['activity_id' => 'لا يمكنك إضافة تقييم جديد لهذا النشاط. لقد قمت بتقييمه مسبقاً.']);
        }

        $questions = AssessmentQuestion::all()->keyBy('id');
        $resultsToCreate = [];
        $hasAnyData = false;
        $currentTime = now();
        $userId = Auth::id();

        // 3. Process Answers and Corresponding Notes
        foreach ($questions as $questionId => $question) {
            $answerKey = 'question_' . $questionId;
            $noteKey = 'note_' . $questionId;
            
            $answerValue = $request->input($answerKey);
            $noteValue = $request->input($noteKey);

            $rangeAnswer = null;
            $textAnswer = null;
            $isAnswerValid = false;
            
            // A. Validate Answer Based on Type
            if ($question->type === 'range') {
                if (is_numeric($answerValue) && $answerValue >= 1 && $answerValue <= $question->max_point) {
                    $rangeAnswer = (int)$answerValue;
                    $isAnswerValid = true;
                }
            } elseif ($question->type === 'text') {
                if (!empty($answerValue)) {
                    $textAnswer = (string)$answerValue;
                    $isAnswerValid = true;
                }
            }

            // B. Determine if a record should be created (Answer OR Note must exist)
            if ($isAnswerValid || !empty($noteValue)) {
                $resultsToCreate[] = [
                    'activity_id' => $activityId,
                    'assessment_question_id' => $questionId,
                    'user_id' => $userId,
                    'range_answer' => $rangeAnswer,
                    'text_answer' => $textAnswer,
                    'note' => $noteValue,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
                $hasAnyData = true;
            }
        }
        
        if (!$hasAnyData) {
             return back()->with('error', 'يجب تقديم إجابة أو ملاحظة واحدة على الأقل لأي سؤال.')->withInput();
        }

        AssessmentResult::insert($resultsToCreate);

        return redirect()->route('activity.show', $activityId)
                         ->with('success', 'تم تسجيل نتائج التقييم بنجاح!');
    }

    /**
     * Show the form for editing the assessment results for a specific activity.
     */
    public function edit(Activity $activity)
    {
        // Check if the user has submitted results (must exist to edit)
        $userResults = AssessmentResult::where('activity_id', $activity->id)
                                       ->where('user_id', Auth::id())
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

    /**
     * Update the assessment results in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        // 1. Basic validation
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
        ]);

        $activityId = $activity->id;
        $userId = Auth::id();
        $questions = AssessmentQuestion::all()->keyBy('id');
        $currentTime = now();
        $hasAnyData = false;
        
        // Fetch all existing result IDs for the current user and activity
        $existingResultIds = AssessmentResult::where('activity_id', $activityId)
                                             ->where('user_id', $userId)
                                             ->pluck('id', 'assessment_question_id')
                                             ->toArray();

        // 2. Prepare for update/insert/delete
        $resultsToUpdate = [];
        $resultIdsToKeep = [];

        foreach ($questions as $questionId => $question) {
            $answerKey = 'question_' . $questionId;
            $noteKey = 'note_' . $questionId;
            
            $answerValue = $request->input($answerKey);
            $noteValue = $request->input($noteKey);

            $rangeAnswer = null;
            $textAnswer = null;
            $isAnswerValid = false;
            
            // Validate Answer Based on Type
            if ($question->type === 'range') {
                if (is_numeric($answerValue) && $answerValue >= 1 && $answerValue <= $question->max_point) {
                    $rangeAnswer = (int)$answerValue;
                    $isAnswerValid = true;
                }
            } elseif ($question->type === 'text') {
                if (!empty($answerValue)) {
                    $textAnswer = (string)$answerValue;
                    $isAnswerValid = true;
                }
            }

            // Determine action (Update/Insert or Delete)
            $isRecordNeeded = $isAnswerValid || !empty($noteValue);

            if ($isRecordNeeded) {
                // Prepare data for update or insert
                $data = [
                    'range_answer' => $rangeAnswer,
                    'text_answer' => $textAnswer,
                    'note' => $noteValue,
                    'updated_at' => $currentTime,
                ];

                if (isset($existingResultIds[$questionId])) {
                    // Update existing record
                    AssessmentResult::where('id', $existingResultIds[$questionId])->update($data);
                    $resultIdsToKeep[] = $existingResultIds[$questionId];
                } else {
                    // Insert new record (Question was not answered previously)
                    $data['activity_id'] = $activityId;
                    $data['assessment_question_id'] = $questionId;
                    $data['user_id'] = $userId;
                    $data['created_at'] = $currentTime;
                    AssessmentResult::insert([$data]);
                }
                $hasAnyData = true;
            }
        }

        // 3. Delete records that are no longer needed (where answer/note was cleared)
        $allExistingIds = array_values($existingResultIds);
        $idsToDelete = array_diff($allExistingIds, $resultIdsToKeep);

        if (!empty($idsToDelete)) {
            AssessmentResult::destroy($idsToDelete);
        }

        if (!$hasAnyData) {
            return back()->with('error', 'يجب أن يحتوي التقييم على إجابة أو ملاحظة واحدة على الأقل.')->withInput();
        }

        return redirect()->route('activity.show', $activityId)
                         ->with('success', 'تم تعديل نتائج التقييم بنجاح!');
    }
}
