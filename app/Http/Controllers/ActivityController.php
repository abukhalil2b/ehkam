<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Activity;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentStage;
use App\Models\Indicator;
use App\Models\Workflow;
use App\Models\WorkflowInstance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{

    public function create(Project $project)
    {
        return view('activity.create', compact('project'));
    }


    public function index()
    {

        $currentStage = AssessmentStage::latest()->first();

        // 2. جلب الأنشطة مع نتائج التقييم الخاصة بالمرحلة الحالية فقط
        $activities = Activity::with([
            'project:id,title',
            'employees',
            'assessmentResults' => function ($query) use ($currentStage) {
                if ($currentStage) {
                    $query->where('assessment_stage_id', $currentStage->id);
                }
            }
        ])
            ->get();

        return view('activity.index', compact('activities', 'currentStage'));
    }

    /**
     * Store a newly created activity in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id|integer',
        ]);

        // 2. Create the new Activity record with workflow defaults
        $activity = Activity::create([
            'title' => $request->title,
            'project_id' => $request->project_id,
            'creator_id' => Auth::id(),
            'status' => 'draft',
        ]);

        // 3. Auto-assign Workflow if available
        $workflow = Workflow::where('entity_type', Activity::class)
            ->where('is_active', true)
            ->first();

        if ($workflow) {
            WorkflowInstance::create([
                'workflow_id' => $workflow->id,
                'workflowable_type' => Activity::class,
                'workflowable_id' => $activity->id,
                'status' => 'draft',
                'creator_id' => Auth::id(),
            ]);
        }

        // 3. Redirect with a success message
        return redirect()->route('project.show', $request->project_id)->with('success', 'النشاط "' . $activity->title . '" تم اضافته !');
    }

    public function show(Activity $activity)
    {
        $activity->load([
            'project',
            'workflowInstance.workflow.stages.team',
            'workflowInstance.currentStage.team',
            'transitions.actor',
            'transitions.fromStage',
            'transitions.toStage',
            'creator',
            'steps',
        ]);


        $allQuestions = AssessmentQuestion::orderBy('ordered')
            ->get(['id', 'content', 'type', 'max_point']);

        // 2️⃣ Prepare range questions & max score
        $rangeQuestions = $allQuestions->where('type', 'range');
        $totalMaxPoints = $rangeQuestions->sum('max_point');

        $userResults = $activity->assessmentResults()
            ->with('assessmentQuestion:id,type', 'user:id,name')
            ->get();

        // 4️⃣ Always initialize summary (NO NULLS)
        $userSummary = [
            'user_name' => '—',
            'total_score' => 0,
            'max_score' => $totalMaxPoints,
            'percentage' => 0,
            'results' => collect(),
        ];

        $hasRangeResults = false;

        if ($userResults->isNotEmpty()) {
            $keyedResults = $userResults->keyBy('assessment_question_id');

            $totalScore = $rangeQuestions->sum(function ($question) use ($keyedResults, &$hasRangeResults) {
                $result = $keyedResults->get($question->id);

                if ($result && is_numeric($result->range_answer)) {
                    $hasRangeResults = true;
                    return $result->range_answer;
                }

                return 0;
            });

            $percentage = $totalMaxPoints > 0
                ? round(($totalScore / $totalMaxPoints) * 100, 1)
                : 0;

            $userSummary = [
                'user_name' => $userResults->first()->user->name ?? '—',
                'total_score' => $totalScore,
                'max_score' => $totalMaxPoints,
                'percentage' => $percentage,
                'results' => $keyedResults,
            ];
        }

        $hasSubmitted = $userResults->isNotEmpty();

        return view('activity.show', [
            'activity' => $activity,
            'allQuestions' => $allQuestions,
            'userSummary' => $userSummary,
            'hasRangeResults' => $hasRangeResults,
            'canSubmitNew' => !$hasSubmitted,
            'canUpdate' => $hasSubmitted,
        ]);
    }
}
