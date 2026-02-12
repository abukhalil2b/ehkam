<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use App\Models\AssessmentStage;
use App\Models\EmployeeAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AssessmentResultController extends Controller
{

    public function show(Activity $activity, AssessmentStage $assessmentStage)
    {
        $userResults = AssessmentResult::where('activity_id', $activity->id)
            ->where('assessment_stage_id', $assessmentStage->id)
            ->with(['assessmentQuestion', 'user']) // تحميل المستخدم
            ->get()
            ->keyBy('assessment_question_id');

        if ($userResults->isEmpty()) {
            return back()->with('error', 'لا توجد بيانات تقييم متاحة.');
        }

        // جلب بيانات المقيم من أول سجل موجود
        $evaluator = $userResults->first()->user;

        $allQuestions = AssessmentQuestion::orderBy('ordered')->get();

        return view('assessment_result.show', compact('activity', 'allQuestions', 'userResults', 'assessmentStage', 'evaluator'));
    }

    /**
     * Show the form for creating a new set of assessment results for a specific activity.
     */
    public function create(Activity $activity)
    {
        // جلب المرحلة الحالية النشطة
        $currentStage = \App\Models\AssessmentStage::latest()->first();

        if (!$currentStage) {
            return back()->with('error', 'لا توجد مرحلة تقييم نشطة حالياً.');
        }

        // التحقق من وجود تقييم لهذا النشاط "في هذه المرحلة فقط"
        $hasSubmitted = AssessmentResult::where('activity_id', $activity->id)
            ->where('assessment_stage_id', $currentStage->id)
            ->exists();

        if ($hasSubmitted) {
            return redirect()->route('assessment_result.edit', $activity->id)
                ->with('warning', 'لقد تم تقييم هذا النشاط في الدورة الحالية. يمكنك التعديل من هنا.');
        }

        $questions = AssessmentQuestion::orderBy('ordered')->get();
        return view('assessment_result.create', compact('activity', 'questions', 'currentStage'));
    }

    /**
     * Show the form for editing the assessment results for a specific activity.
     */
    public function edit(Activity $activity)
    {
        $currentStage = \App\Models\AssessmentStage::latest()->first();

        if (!$currentStage) {
            return back()->with('error', 'لا توجد مرحلة تقييم نشطة.');
        }

        $userResults = AssessmentResult::where('activity_id', $activity->id)
            ->where('assessment_stage_id', $currentStage->id)
            ->get()
            ->keyBy('assessment_question_id');

        // إذا لم يجد نتائج لهذه المرحلة، يوجهه للإنشاء
        if ($userResults->isEmpty()) {
            return redirect()->route('assessment_result.create', $activity->id);
        }

        $allQuestions = AssessmentQuestion::orderBy('ordered')->get();
        return view('assessment_result.edit', compact('activity', 'allQuestions', 'userResults', 'currentStage'));
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
        // 1. استخراج المعرفات الأساسية
        $activityId = $activityId ?? $request->input('activity_id');

        // التحقق من المدخلات الأساسية
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
        ]);

        // 2. جلب البيانات الضرورية مرة واحدة (Eager Loading)
        $activity = Activity::with('project')->findOrFail($activityId);
        $currentStage = AssessmentStage::latest()->first();

        if (!$currentStage) {
            return back()->with('error', 'لا يمكن حفظ التقييم، لم يتم تحديد مرحلة تقييم نشطة.');
        }

        // 3. التحقق من منصب الموظف
        $userId = Auth::id();
        $position = EmployeeAssignment::where('user_id', $userId)->latest()->first();
        $positionId = $position?->position_id;

        // 4. معالجة الأسئلة
        $questions = AssessmentQuestion::all();
        $resultsToCreate = [];
        $hasAnyData = false;
        $currentTime = now();

        foreach ($questions as $question) {
            $answerValue = $request->input("question_{$question->id}");
            $noteValue   = $request->input("note_{$question->id}");

            $rangeAnswer = null;
            $textAnswer  = null;

            // منطق التحقق من نوع السؤال وقيمته
            if ($question->type === 'range' && is_numeric($answerValue)) {
                $rangeAnswer = (int) $answerValue;
                if ($rangeAnswer >= 1 && $rangeAnswer <= $question->max_point) {
                    $hasAnyData = true;
                } else {
                    $rangeAnswer = null; // إعادة التعيين إذا كانت خارج النطاق
                }
            } elseif ($question->type === 'text' && filled($answerValue)) {
                $textAnswer = (string) $answerValue;
                $hasAnyData = true;
            }

            if (filled($noteValue)) {
                $hasAnyData = true;
            }

            // إضافة السجل فقط إذا كان يحتوي على بيانات (إجابة أو ملاحظة)
            if ($rangeAnswer !== null || $textAnswer !== null || filled($noteValue)) {
                $resultsToCreate[] = [
                    'activity_id'            => $activityId,
                    'assessment_question_id' => $question->id,
                    'assessment_stage_id'    => $currentStage->id,
                    'user_id'                => $userId,
                    'position_id'            => $positionId,
                    'range_answer'           => $rangeAnswer,
                    'text_answer'            => $textAnswer,
                    'note'                   => $noteValue,
                    'created_at'             => $currentTime,
                    'updated_at'             => $currentTime,
                ];
            }
        }

        if (!$hasAnyData) {
            return back()->with('error', 'يجب تقديم إجابة أو ملحوظة واحدة على الأقل.')->withInput();
        }

        // 5. استخدام Database Transaction لضمان سلامة البيانات
        // هذا يضمن أنه إذا فشل الإدخال، فلن يتم حذف البيانات القديمة
        DB::transaction(function () use ($activityId, $currentStage, $resultsToCreate) {
            AssessmentResult::where('activity_id', $activityId)
                ->where('assessment_stage_id', $currentStage->id)
                ->delete();

            AssessmentResult::insert($resultsToCreate);
        });

        return redirect()->route('activity.index', $activity->project->indicator_id)
            ->with('success', "تم تسجيل نتائج التقييم بنجاح لمرحلة: {$currentStage->title}");
    }
}
