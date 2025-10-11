<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AssessmentQuestionController;
use App\Http\Controllers\AssessmentResultController;
use App\Http\Controllers\ContributeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\IndicatorFeedbackController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StatisticController;
use App\Models\Activity;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Dashboard
Route::group(['middleware' => ['auth']], function () {

    Route::get('dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('indicator_feedback/show/{indicator_id}', [IndicatorFeedbackController::class, 'show'])
        ->name('indicator_feedback.show');
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('indicator/target/{indicator}', [IndicatorController::class, 'target'])
        ->name('indicator.target');

    Route::get('indicator/achieved/{indicator}', [IndicatorController::class, 'achieved'])
        ->name('indicator.achieved');

    Route::get('indicator/show/{indicator}', [IndicatorController::class, 'show'])
        ->name('indicator.show');

    Route::get('indicator/index', [IndicatorController::class, 'index'])
        ->name('indicator.index');

    Route::get('indicator/create', [IndicatorController::class, 'create'])
        ->name('indicator.create');

    Route::post('indicator/store', [IndicatorController::class, 'store'])
        ->name('indicator.store');
});



Route::group(['middleware' => ['auth']], function () {
    Route::get('project/index', [ProjectController::class, 'index'])
        ->name('project.index');

        Route::get('project/edit/{project}', [ProjectController::class, 'edit'])
        ->name('project.edit');


// Route for submitting the updated data (PUT/PATCH request)
Route::put('project/update/{project}', [ProjectController::class, 'update'])
        ->name('project.update');

    Route::get('project/create', [ProjectController::class, 'create'])
        ->name('project.create');

    Route::post('project/store', [ProjectController::class, 'store'])
        ->name('project.store');

    Route::get('project/show/{project}', [ProjectController::class, 'show'])
        ->name('project.show');

    Route::get('project/steps/show/{project}', [ProjectController::class, 'stepsShow'])
        ->name('project.steps.show');
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('activity/index', [ActivityController::class, 'index'])
        ->name('activity.index');

    Route::get('activity/create', [ActivityController::class, 'create'])
        ->name('activity.create');

    Route::post('activity/store', [ActivityController::class, 'store'])
        ->name('activity.store');

    Route::get('activity/show/{activity}', [ActivityController::class, 'show'])
        ->name('activity.show');

    Route::get('assessment_result/create/{activity}', [AssessmentResultController::class, 'create'])
        ->name('assessment_result.create');

    Route::get('assessment_result/edit/{activity}', [AssessmentResultController::class, 'edit'])
        ->name('assessment_result.edit');

    Route::patch('assessment_result/update/{activity}', [AssessmentResultController::class, 'update'])
        ->name('assessment_result.update');

    Route::post('assessment_result/store', [AssessmentResultController::class, 'store'])
        ->name('assessment_result.store');

    Route::get('assessment_questions/create', [AssessmentQuestionController::class, 'create'])
        ->name('assessment_questions.create');

    Route::post('assessment_questions/store', [AssessmentQuestionController::class, 'store'])
        ->name('assessment_questions.store');

    Route::get('assessment_questions/index', [AssessmentQuestionController::class, 'index'])
        ->name('assessment_questions.index');

    Route::get('assessment_questions/edit/{question}', [AssessmentQuestionController::class, 'edit'])
        ->name('assessment_questions.edit');

    Route::put('assessment_questions/update/{question}', [AssessmentQuestionController::class, 'update'])
        ->name('assessment_questions.update');

    Route::get('assessment_report', [ReportController::class, 'projectAssessmentReport'])
        ->name('assessment.report');
});

Route::get('statistic/index', [StatisticController::class, 'index'])
    ->name('statistic.index');

Route::get('statistic/quran', [StatisticController::class, 'quran'])
    ->name('statistic.quran');

Route::view('report', 'report')
    ->middleware(['auth'])
    ->name('report');

Route::view('achievements', 'achievements')
    ->middleware(['auth'])
    ->name('achievements');

Route::view('task/index', 'task.index')
    ->middleware(['auth'])
    ->name('task.index');


Route::view('my-tasks', 'my-tasks')
    ->middleware(['auth'])
    ->name('my-tasks');

// Profile
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


// kanpan
Route::view('kanban', 'kanban')
    ->middleware(['auth'])
    ->name('kanban');

Route::view('kanban2', 'kanban2')
    ->middleware(['auth'])
    ->name('kanban2');

//staff
Route::view('staff_index', 'staff_index')
    ->middleware(['auth'])
    ->name('staff_index');

//question
Route::view('question_result', 'question_result')
    ->middleware(['auth'])
    ->name('question_result');

Route::group(['middleware' => ['auth']], function () {

    Route::get('questionnaire/index', [QuestionnaireController::class, 'index'])
        ->name('questionnaire.index');

    Route::get('questionnaire/create', [QuestionnaireController::class, 'create'])
        ->name('questionnaire.create');

    Route::get('questionnaire/show/{questionnaire}', [QuestionnaireController::class, 'show'])
        ->name('questionnaire.show');

    Route::get('questionnaire/edit/{questionnaire}', [QuestionnaireController::class, 'edit'])
        ->name('questionnaire.edit');

    Route::put('questionnaire/update/{questionnaire}', [QuestionnaireController::class, 'update'])
        ->name('questionnaire.update');

    Route::get('questionnaire/question_edit/{questionnaire}', [QuestionnaireController::class, 'question_edit'])
        ->name('questionnaire.question_edit');

    Route::put('questionnaire/question_update/{questionnaire}', [QuestionnaireController::class, 'question_update'])
        ->name('questionnaire.question_update');

    Route::get('questionnaire/take/{questionnaire}', [QuestionnaireController::class, 'take'])
        ->name('questionnaire.take');

    Route::get('questionnaire/duplicate/{questionnaire}', [QuestionnaireController::class, 'duplicate'])
        ->name('questionnaire.duplicate');

    Route::post('questionnaire/store', [QuestionnaireController::class, 'store'])
        ->name('questionnaire.store');

    Route::post('questionnaire/submit/{questionnaire}', [QuestionnaireController::class, 'submit'])
        ->name('questionnaire.submit');

    Route::delete('questionnaire/delete/{questionnaire}', [QuestionnaireController::class, 'delete'])
        ->name('questionnaire.delete');

    Route::get('questionnaire/answer_index/{questionnaire}', [QuestionnaireController::class, 'answer_index'])
        ->name('questionnaire.answer_index');

    Route::get('questionnaire/answer_show/{answer}', [QuestionnaireController::class, 'answerShow'])
        ->name('questionnaire.answer_show');

    Route::put('questionnaire/answer_update/{answer}', [QuestionnaireController::class, 'updateAnswer'])
        ->name('questionnaire.answer_update');

    Route::get('questionnaire/export', [QuestionnaireController::class, 'export'])
        ->name('questionnaire.export');

    Route::get('questionnaire/statistics/{questionnaire}', [QuestionnaireController::class, 'statistics'])
        ->name('questionnaire.statistics');
});
Route::get('q/{hash}', [QuestionnaireController::class, 'publicTake'])
    ->name('questionnaire.public_take');
Route::post('q/submit/{hash}', [QuestionnaireController::class, 'publicSubmit'])
    ->name('questionnaire.public_submit');

require __DIR__ . '/auth.php';
Route::post('logout', [DashboardController::class, 'logout'])
    ->middleware(['auth'])
    ->name('logout');
