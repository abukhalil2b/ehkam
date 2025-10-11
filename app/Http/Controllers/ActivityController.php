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
    /**
     * Display a listing of the activity.
     */
    public function index()
    {
        // Fetch all activities, ordered by creation date, for the index view
        // Adjust the query if you need to filter by user or project.
        $activities = Activity::latest()->get();

        return view('activity.index', compact('activities'));
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
            'project_id' => $request->project_id
        ]);

        // 3. Redirect with a success message
        return redirect()->route('activity.index')->with('success', 'النشاط "' . $activity->title . '" تم اضافته !');
    }

    public function show(Activity $activity)
    {
        $allQuestions = AssessmentQuestion::orderBy('ordered')->get();
        $totalMaxPoints = 0;

        // Calculate the theoretical maximum possible score for range questions
        $rangeQuestions = $allQuestions->where('type', 'range');
        foreach ($rangeQuestions as $q) {
            $totalMaxPoints += $q->max_point;
        }

        // Fetch all existing results for this activity, grouped by user, and key by question ID for efficiency
        $assessmentResultsByUser = $activity->assessmentResults()
            ->with('assessmentQuestion', 'user')
            ->get()
            ->groupBy('user_id');

        $summary = [];
        $hasRangeResults = false;

        foreach ($assessmentResultsByUser as $userId => $userResults) {
            $totalScore = 0;
            $answeredRangeQuestions = 0;

            // Key results by question ID for easy lookup
            $keyedResults = $userResults->keyBy('assessment_question_id');

            // Loop through all range questions to calculate the user's total score
            foreach ($rangeQuestions as $question) {
                $result = $keyedResults->get($question->id);
                if ($result && is_numeric($result->range_answer)) {
                    $totalScore += $result->range_answer;
                    $answeredRangeQuestions++;
                    $hasRangeResults = true;
                }
            }

            $percentage = $totalMaxPoints > 0 ? ($totalScore / $totalMaxPoints) * 100 : 0;

            $summary[$userId] = [
                'user_name' => $userResults->first()->user->name ?? 'مستخدم غير معروف',
                'total_score' => $totalScore,
                'max_score' => $totalMaxPoints,
                'percentage' => round($percentage, 1),
                'results' => $keyedResults, // Keep keyed results for detailed view
            ];
        }

        // Check if the current authenticated user has submitted a result
        $userSubmitted = AssessmentResult::where('activity_id', $activity->id)
            ->where('user_id', Auth::id())
            ->exists();


        return view('activity.show', compact(
            'activity',
            'allQuestions',
            'assessmentResultsByUser',
            'summary',
            'hasRangeResults',
            'userSubmitted'
        ));
    }
}
