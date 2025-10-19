<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Activity;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{

    public function index()
    {
        $currentYear = now()->year;

        // Eager load the AssessmentResults and their associated User models to show who submitted each assessment, 
        // preventing the N+1 query problem.
        $activities = Activity::where('current_year', $currentYear)
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
            'currentYear' => $currentYear, // Pass the year for display in the view
        ]);
    }

    // The create method isn't strictly necessary for the route definition provided,
    // but it's good practice to have if you plan on using a dedicated create form.
    public function create()
    {
        // You would typically load data needed for the form here, e.g., Projects.
        $projects = Project::all();
        return view('activity.create', compact('projects'));
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

        // 2. Create the new Activity record
        $activity = Activity::create([
            'title' => $request->title,
            'project_id' => $request->project_id,
            'current_year' => now()->year
        ]);

        // 3. Redirect with a success message
        return redirect()->route('activity.index')->with('success', 'النشاط "' . $activity->title . '" تم اضافته !');
    }

    public function show(Activity $activity)
    {
        $currentYear = now()->year;

        // 1️⃣ Load only questions for the current assessment year
        $allQuestions = AssessmentQuestion::where('assessment_year', $currentYear)
            ->orderBy('ordered')
            ->get();

        // 2️⃣ Calculate total possible points for range-type questions
        $rangeQuestions = $allQuestions->where('type', 'range');
        $totalMaxPoints = $rangeQuestions->sum('max_point');

        // 3️⃣ Fetch current user's assessment results for this activity & year
        $userResults = $activity->assessmentResults()
            ->with('assessmentQuestion', 'user')
            ->whereHas('assessmentQuestion', function ($q) use ($currentYear) {
                $q->where('assessment_year', $currentYear);
            })
            ->get();

        $userSummary = null;
        $hasRangeResults = false;

        if ($userResults->isNotEmpty()) {
            $totalScore = 0;
            $keyedResults = $userResults->keyBy('assessment_question_id');

            foreach ($rangeQuestions as $question) {
                $result = $keyedResults->get($question->id);
                if ($result && is_numeric($result->range_answer)) {
                    $totalScore += $result->range_answer;
                    $hasRangeResults = true;
                }
            }

            $percentage = $totalMaxPoints > 0 ? ($totalScore / $totalMaxPoints) * 100 : 0;

            $userSummary = [
                'user_name'   => $userResults->first()->user->name ?? '—',
                'total_score' => $totalScore,
                'max_score'   => $totalMaxPoints,
                'percentage'  => round($percentage, 1),
                'results'     => $keyedResults,
            ];
        }

        // 4️⃣ Determine if user can submit new or update
        $canSubmitNew = $userResults->isEmpty();
        $canUpdate    = $userResults->isNotEmpty();

        return view('activity.show', compact(
            'activity',
            'allQuestions',
            'userResults',
            'userSummary',
            'hasRangeResults',
            'canSubmitNew',
            'canUpdate',
            'currentYear'
        ));
    }
}
