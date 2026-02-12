<?php

namespace App\Http\Controllers;

use App\Models\Project; // Assuming you have a Project model
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use App\Models\AssessmentStage;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function projectAssessmentReport($assessmentStageId = null)
{
    // 1. تحديد المرحلة المستهدفة
    // إذا لم يتم تمرير ID، نأخذ أحدث مرحلة تحتوي على نتائج، أو أحدث مرحلة مسجلة
    if (!$assessmentStageId) {
        $latestWithResults = AssessmentResult::latest()->first();
        $targetStageId = $latestWithResults ? $latestWithResults->assessment_stage_id : AssessmentStage::latest()->value('id');
    } else {
        $targetStageId = $assessmentStageId;
    }

    $currentAssessmentStage = AssessmentStage::find($targetStageId);
    $assessmentStages = AssessmentStage::all();

    if (!$currentAssessmentStage) {
        return back()->with('error', 'لا توجد مراحل تقييم نشطة.');
    }

    // 2. جلب البيانات مع الفلترة الصارمة بالمرحلة
    $projects = Project::with(['activities.assessmentResults' => function($q) use ($targetStageId) {
        $q->where('assessment_stage_id', $targetStageId);
    }, 'activities.assessmentResults.assessmentQuestion'])->get();

    $maxPointsPerQuestion = AssessmentQuestion::where('type', 'range')->pluck('max_point', 'id');

    $reportData = collect();

    foreach ($projects as $project) {
        $projectTotalScore = 0;
        $projectTotalMaxScore = 0;
        $projectActivities = collect();

        foreach ($project->activities as $activity) {
            $activityTotalScore = $activity->assessmentResults->sum('range_answer');
            $activityTotalMaxScore = 0;

            foreach ($activity->assessmentResults as $result) {
                $maxPossible = $maxPointsPerQuestion->get($result->assessment_question_id);
                if ($maxPossible) {
                    $activityTotalMaxScore += (int)$maxPossible;
                }
            }

            $activityPercentage = $activityTotalMaxScore > 0 
                ? round(($activityTotalScore / $activityTotalMaxScore) * 100, 1) 
                : 0;

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

        $projectFinalPercentage = $projectTotalMaxScore > 0 
            ? round(($projectTotalScore / $projectTotalMaxScore) * 100, 1) 
            : 0;

        $reportData->push([
            'project_title' => $project->title,
            'total_percentage' => $projectFinalPercentage,
            'total_score' => $projectTotalScore,
            'max_score' => $projectTotalMaxScore,
            'activities' => $projectActivities,
        ]);
    }

    return view('reports.assessment_report', compact('reportData', 'currentAssessmentStage', 'assessmentStages'));
}
}
