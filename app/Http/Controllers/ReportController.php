<?php

namespace App\Http\Controllers;

use App\Models\Project; // Assuming you have a Project model
use App\Models\AssessmentQuestion;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function projectAssessmentReport($currentYear = null)
    {
        $currentYear = $currentYear ? (int)$currentYear : now()->year;

        $years = range(2025, 2040);
        if (! in_array($currentYear, $years)) {
            abort(403, 'لقد اخترت سنة تقرير الخطأ');
        }

        // 1. Determine the Current Year
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        // 2. Get all Projects for the current year with their activities and related assessment results
        $projects = Project::where('current_year', $currentYear)
            ->with([
                'activities.assessmentResults' => fn($q) => $q->whereHas('assessmentQuestion', fn($q) => $q->where('assessment_year', $currentYear))
                    ->with('assessmentQuestion')
            ])
            ->get();

        // 3. Get the maximum points for each range question (to calculate max possible score)
        $maxPointsPerQuestion = AssessmentQuestion::where('type', 'range')
            ->where('assessment_year', $currentYear)
            ->pluck('max_point', 'id');

        $reportData = collect();

        // 4. Loop through Projects and calculate scores
        foreach ($projects as $project) {
            $projectTotalScore = 0;
            $projectTotalMaxScore = 0;
            $projectActivities = collect();

            // 5. Loop through Activities within the project
            foreach ($project->activities as $activity) {
                $activityTotalScore = 0;
                $activityTotalMaxScore = 0;

                // Group results by question ID to easily check scores
                $resultsByQuestion = $activity->assessmentResults->keyBy('assessment_question_id');

                // Calculate score for the current activity
                foreach ($resultsByQuestion as $result) {
                    $questionId = $result->assessment_question_id;
                    $maxScore = $maxPointsPerQuestion->get($questionId);

                    // Only count range answers and ensure data is valid
                    if ($maxScore && $result->range_answer !== null) {
                        $activityTotalScore += $result->range_answer;
                        $activityTotalMaxScore += $maxScore;
                    }
                }

                // Calculate percentage for the current activity
                $activityPercentage = $activityTotalMaxScore > 0
                    ? round(($activityTotalScore / $activityTotalMaxScore) * 100, 1)
                    : 0;

                // Accumulate totals for the project
                $projectTotalScore += $activityTotalScore;
                $projectTotalMaxScore += $activityTotalMaxScore;

                $projectActivities->push([
                    'id' => $activity->id,
                    'title' => $activity->title,
                    'total_score' => $activityTotalScore,
                    'max_score' => $activityTotalMaxScore,
                    'percentage' => $activityPercentage,
                ]);
            }

            // Calculate final project percentage
            $projectFinalPercentage = $projectTotalMaxScore > 0
                ? round(($projectTotalScore / $projectTotalMaxScore) * 100, 1)
                : 0;

            // Store final project data
            $reportData->push([
                'project_title' => $project->title,
                'total_percentage' => $projectFinalPercentage,
                'total_score' => $projectTotalScore,
                'max_score' => $projectTotalMaxScore,
                'activities' => $projectActivities,
            ]);
        }

        // 6. Pass the report data AND the current year to the view
        return view('reports.assessment_report', compact('reportData', 'currentYear', 'years'));
    }
}
