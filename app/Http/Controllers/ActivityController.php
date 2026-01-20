<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Activity;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use App\Models\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{

    public function index($year)
    {
        $availableYears = Indicator::query()
            ->select('current_year')
            ->distinct()
            ->pluck('current_year')
            ->toArray();

        // Fallback to most recent year if invalid year is requested
        if (!in_array($year, $availableYears)) {
            $year = $availableYears[0] ?? now()->year;
        }

        // Eager load the AssessmentResults and their associated User models to show who submitted each assessment, 
        // preventing the N+1 query problem.
        $activities = Activity::where('current_year', $year)
            ->with('assessmentResults.user')
            ->latest()
            ->get();

        // 3. Check if the user is authenticated
        $userId = Auth::id();
        $submittedActivityIds = [];

        if ($userId) {
            // 4. Efficiently fetch the IDs of all activities the authenticated user has submitted a result for
            // NOTE: This part remains the same as it checks against all submitted results, 
            // regardless of the activity's year, ensuring consistency if a result was submitted later.
            $submittedActivityIds = AssessmentResult::pluck('activity_id') // Pluck only the activity_id column
                ->unique()             // Ensure unique IDs
                ->toArray();
        }

        // Pass activities, the array of submitted IDs, and the current year to the view
        return view('activity.index', [
            'activities' => $activities,
            'submittedActivityIds' => $submittedActivityIds,
            'availableYears' => $availableYears,
            'selectedYear' => $year, // Pass the year for display in the view
        ]);
    }

    public function create(Project $project)
    {
        return view('activity.create', compact('project'));
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
            'current_year' => now()->year,
            'creator_id' => Auth::id(),
            'status' => 'draft',
        ]);

        // 3. Auto-assign Workflow if available
        $workflow = \App\Models\Workflow::where('entity_type', \App\Models\Activity::class)
            ->where('is_active', true)
            ->first();

        if ($workflow) {
            \App\Models\WorkflowInstance::create([
                'workflow_id' => $workflow->id,
                'workflowable_type' => \App\Models\Activity::class,
                'workflowable_id' => $activity->id,
                'status' => 'draft',
                'creator_id' => \Illuminate\Support\Facades\Auth::id(),
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

        $currentYear = $activity->current_year;

        // 1️⃣ Load assessment questions for the current year
        $allQuestions = AssessmentQuestion::where('assessment_year', $currentYear)
            ->orderBy('ordered')
            ->get(['id', 'content', 'type', 'max_point']);

        // 2️⃣ Prepare range questions & max score
        $rangeQuestions = $allQuestions->where('type', 'range');
        $totalMaxPoints = $rangeQuestions->sum('max_point');

        // 3️⃣ Load user results for this activity & year
        $userResults = $activity->assessmentResults()
            ->with('assessmentQuestion:id,type', 'user:id,name')
            ->whereHas('assessmentQuestion', function ($q) use ($currentYear) {
                $q->where('assessment_year', $currentYear);
            })
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
            'currentYear' => $currentYear,
        ]);
    }
}
