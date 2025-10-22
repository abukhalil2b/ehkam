<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssessmentQuestionController;
use App\Http\Controllers\AssessmentResultController;
use App\Http\Controllers\ContributeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\IndicatorFeedbackController;
use App\Http\Controllers\MeetingMinuteController;
use App\Http\Controllers\OrganizationalUnitController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\TaskController;
use App\Models\Activity;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Dashboard - Usually just requires authentication
Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');
});

// Indicator Feedback (Assuming viewing feedback is part of indicator access)
Route::group(['middleware' => ['auth', 'permission:indicator_feedback.show']], function () {
    Route::get('indicator_feedback/show/{indicator_id}', [IndicatorFeedbackController::class, 'show'])
        ->name('indicator_feedback.show');
});

// INDICATOR ROUTES
Route::group(['middleware' => ['auth']], function () {
    // Read Permissions (index/show)
    Route::get('indicator/target/{indicator}', [IndicatorController::class, 'target'])
        ->middleware('permission:indicator.index')
        ->name('indicator.target');

    Route::get('indicator/achieved/{indicator}', [IndicatorController::class, 'achieved'])
        ->middleware('permission:indicator.index')
        ->name('indicator.achieved');

    Route::get('indicator/show/{indicator}', [IndicatorController::class, 'show'])
        ->middleware('permission:indicator.index')
        ->name('indicator.show');

    Route::get('indicator/index', [IndicatorController::class, 'index'])
        ->middleware('permission:indicator.index')
        ->name('indicator.index');

    // Create/Write Permissions
    Route::get('indicator/create', [IndicatorController::class, 'create'])
        ->middleware('permission:indicator.create')
        ->name('indicator.create');

    Route::post('indicator/store', [IndicatorController::class, 'store'])
        ->middleware('permission:indicator.create')
        ->name('indicator.store');
    Route::get('indicator/edit/{indicator}', [IndicatorController::class, 'edit'])
        ->middleware('permission:indicator.edit')
        ->name('indicator.edit');

    Route::put('indicator/update/{indicator}', [IndicatorController::class, 'update'])
        ->middleware('permission:indicator.edit')
        ->name('indicator.update');

    // Delete Permission
    Route::delete('indicator/destroy/{indicator}', [IndicatorController::class, 'destroy'])
        ->middleware('permission:indicator.delete')
        ->name('indicator.destroy');
});

// PROJECT ROUTES
Route::group(['middleware' => ['auth']], function () {
    Route::get('project/index/{indicator}', [ProjectController::class, 'index'])
        ->middleware('permission:project.index')
        ->name('project.index');

    // Edit/Update Permissions
    Route::get('project/edit/{project}', [ProjectController::class, 'edit'])
        ->middleware('permission:project.edit')
        ->name('project.edit');

    Route::put('project/update/{project}', [ProjectController::class, 'update'])
        ->middleware('permission:project.edit')
        ->name('project.update');

    // Create/Write Permissions
    Route::get('project/create/{indicator}', [ProjectController::class, 'create'])
        ->middleware('permission:project.create')
        ->name('project.create');

    Route::post('project/store', [ProjectController::class, 'store'])
        ->middleware('permission:project.create')
        ->name('project.store');

    Route::get('project/show/{project}', [ProjectController::class, 'show'])
        ->middleware('permission:project.index')
        ->name('project.show');

    Route::get('project/task/show/{project}', [ProjectController::class, 'taskShow'])
        ->middleware('permission:project.index') // Assuming view access is sufficient
        ->name('project.task.show');
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('step/index/{project}', [StepController::class, 'index'])
        ->name('step.index');

    Route::post('step/store/{project}', [StepController::class, 'store'])->name('step.store');

    Route::get('step/show/{step}', [StepController::class, 'show'])
        ->name('step.show');

    Route::post('step/{step}/upload_evidence', [StepController::class, 'uploadEvidence'])->name('step.uploadEvidence');

    Route::get('step/{step}/edit', [StepController::class, 'edit'])->name('step.edit');
    Route::put('step/{step}', [StepController::class, 'update'])->name('step.update');

    Route::delete('step/{step}', [StepController::class, 'destroy'])
        ->name('step.destroy');
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('meeting_minute/index', [MeetingMinuteController::class, 'index'])
        ->middleware('permission:meeting_minute.show')
        ->name('meeting_minute.index');

    Route::get('meeting_minute/create', [MeetingMinuteController::class, 'create'])
        ->middleware('permission:meeting_minute.create')
        ->name('meeting_minute.create');

    Route::post('meeting_minute/store', [MeetingMinuteController::class, 'store'])
        ->middleware('permission:meeting_minute.create')
        ->name('meeting_minute.store');

    Route::get('meeting_minute/show/{meeting_minute}', [MeetingMinuteController::class, 'show'])
        ->middleware('permission:meeting_minute.show')
        ->name('meeting_minute.show');

    Route::get('meeting_minute/edit/{meeting_minute}', [MeetingMinuteController::class, 'edit'])
        ->middleware('permission:meeting_minute.edit')
        ->name('meeting_minute.edit');

    Route::put('meeting_minute/update/{meeting_minute}', [MeetingMinuteController::class, 'update'])
        ->middleware('permission:meeting_minute.edit')
        ->name('meeting_minute.update');

    Route::delete('meeting_minute/destroy/{meeting_minute}', [MeetingMinuteController::class, 'destroy'])
        ->middleware('permission:meeting_minute.delete')
        ->name('meeting_minute.destroy');
});



// ACTIVITY & ASSESSMENT ROUTES
Route::group(['middleware' => ['auth']], function () {
    // Activity
    Route::get('activity/index', [ActivityController::class, 'index'])
        ->middleware('permission:activity.index')
        ->name('activity.index');

    Route::get('activity/create', [ActivityController::class, 'create'])
        ->middleware('permission:activity.create')
        ->name('activity.create');

    Route::post('activity/store', [ActivityController::class, 'store'])
        ->middleware('permission:activity.create')
        ->name('activity.store');

    Route::get('activity/show/{activity}', [ActivityController::class, 'show'])
        ->middleware('permission:activity.index')
        ->name('activity.show');

    // Assessment Results (Usually part of activity management)
    Route::get('assessment_result/create/{activity}', [AssessmentResultController::class, 'create'])
        ->middleware('permission:assessment_result.create')
        ->name('assessment_result.create');

    Route::get('assessment_result/edit/{activity}', [AssessmentResultController::class, 'edit'])
        ->middleware('permission:assessment_result.edit')
        ->name('assessment_result.edit');

    Route::patch('assessment_result/update/{activity}', [AssessmentResultController::class, 'update'])
        ->middleware('permission:assessment_result.edit')
        ->name('assessment_result.update');

    Route::post('assessment_result/store', [AssessmentResultController::class, 'store'])
        ->middleware('permission:assessment_result.create')
        ->name('assessment_result.store');

    // Assessment Questions
    Route::get('assessment_questions/create', [AssessmentQuestionController::class, 'create'])
        ->middleware('permission:assessment_questions.create')
        ->name('assessment_questions.create');

    Route::post('assessment_questions/store', [AssessmentQuestionController::class, 'store'])
        ->middleware('permission:assessment_questions.create')
        ->name('assessment_questions.store');

    Route::get('assessment_questions/index', [AssessmentQuestionController::class, 'index'])
        ->middleware('permission:assessment_questions.index')
        ->name('assessment_questions.index');

    Route::get('assessment_questions/edit/{question}', [AssessmentQuestionController::class, 'edit'])
        ->middleware('permission:assessment_questions.edit')
        ->name('assessment_questions.edit');

    Route::put('assessment_questions/update/{question}', [AssessmentQuestionController::class, 'update'])
        ->middleware('permission:assessment_questions.edit')
        ->name('assessment_questions.update');

    Route::get('project_assessment_report/{year?}', [ReportController::class, 'projectAssessmentReport'])
        ->middleware('permission:project_assessment_report')
        ->name('project_assessment_report');

    Route::get('assessment_questions/update_ordered', [AssessmentQuestionController::class, 'updateOrdered'])
        ->middleware('permission:assessment_questions.edit') // Assuming update requires edit permission
        ->name('assessment_questions.update_ordered');
});

// STATISTIC ROUTES (Assuming these are for viewing reports/data)
Route::get('statistic/index', [StatisticController::class, 'index'])
    ->middleware('permission:statistic.index')
    ->name('statistic.index');
Route::get('questionnaire/{questionnaire}/share', [QuestionnaireController::class, 'shareLink'])
    ->name('questionnaire.share_link');
Route::get('statistic/quran', [StatisticController::class, 'quran'])
    ->middleware('permission:statistic.quran')
    ->name('statistic.quran');

// QUESTIONNAIRE ROUTES
Route::group(['middleware' => ['auth']], function () {
    Route::get('questionnaire/index', [QuestionnaireController::class, 'index'])
        ->middleware('permission:questionnaire.index')
        ->name('questionnaire.index');

    // Create/Write Permissions
    Route::get('questionnaire/create', [QuestionnaireController::class, 'create'])
        ->middleware('permission:questionnaire.create')
        ->name('questionnaire.create');

    Route::post('questionnaire/store', [QuestionnaireController::class, 'store'])
        ->middleware('permission:questionnaire.create')
        ->name('questionnaire.store');

    // Edit/Update Permissions
    Route::get('questionnaire/edit/{questionnaire}', [QuestionnaireController::class, 'edit'])
        ->middleware('permission:questionnaire.edit')
        ->name('questionnaire.edit');

    Route::put('questionnaire/update/{questionnaire}', [QuestionnaireController::class, 'update'])
        ->middleware('permission:questionnaire.edit')
        ->name('questionnaire.update');

    // Viewing and management
    Route::get('questionnaire/show/{questionnaire}', [QuestionnaireController::class, 'show'])
        ->middleware('permission:questionnaire.index') // Viewing is part of index
        ->name('questionnaire.show');

    Route::get('questionnaire/question_edit/{questionnaire}', [QuestionnaireController::class, 'question_edit'])
        ->middleware('permission:questionnaire.edit')
        ->name('questionnaire.question_edit');

    Route::put('questionnaire/question_update/{questionnaire}', [QuestionnaireController::class, 'question_update'])
        ->middleware('permission:questionnaire.edit')
        ->name('questionnaire.question_update');

    Route::get('questionnaire/take/{questionnaire}', [QuestionnaireController::class, 'take'])
        ->name('questionnaire.take'); // Usually open to all authenticated users

    Route::get('questionnaire/duplicate/{questionnaire}', [QuestionnaireController::class, 'duplicate'])
        ->middleware('permission:questionnaire.create') // Duplicating is like creating
        ->name('questionnaire.duplicate');

    Route::post('questionnaire/submit/{questionnaire}', [QuestionnaireController::class, 'submit'])
        ->name('questionnaire.submit'); // Usually open to all authenticated users

    Route::delete('questionnaire/delete/{questionnaire}', [QuestionnaireController::class, 'delete'])
        ->middleware('permission:questionnaire.delete')
        ->name('questionnaire.delete');

    Route::get('questionnaire/answer_index/{questionnaire}', [QuestionnaireController::class, 'answer_index'])
        ->middleware('permission:questionnaire.answer_index')
        ->name('questionnaire.answer_index');

    Route::get('questionnaire/answer_show/{answer}', [QuestionnaireController::class, 'answerShow'])
        ->middleware('permission:questionnaire.answer_index') // Viewing answer is part of index
        ->name('questionnaire.answer_show');

    Route::put('questionnaire/answer_update/{answer}', [QuestionnaireController::class, 'updateAnswer'])
        ->middleware('permission:questionnaire.answer_edit')
        ->name('questionnaire.answer_update');

    Route::get('questionnaire/export', [QuestionnaireController::class, 'export'])
        ->middleware('permission:questionnaire.export')
        ->name('questionnaire.export');

    Route::get('questionnaire/statistics/{questionnaire}', [QuestionnaireController::class, 'statistics'])
        ->middleware('permission:questionnaire.index') // Viewing stats is part of index
        ->name('questionnaire.statistics');
});

// Public Questionnaire Routes (No 'auth' middleware needed)
Route::get('q/{hash}', [QuestionnaireController::class, 'publicTake'])
    ->name('questionnaire.public_take');

Route::post('q/submit/{hash}', [QuestionnaireController::class, 'publicSubmit'])
    ->name('questionnaire.public_submit');


// TASK/KANBAN ROUTES (Using generic task permission)
Route::group(['middleware' => ['auth', 'permission:task.index']], function () {
    Route::get('task/index', [TaskController::class, 'index'])
        ->name('task.index');

    Route::get('kanban', [TaskController::class, 'kanban'])
        ->name('kanban');

    Route::get('kanban2', [TaskController::class, 'kanban2'])
        ->name('kanban2');

    Route::get('staff_index', [TaskController::class, 'staff_index'])
        ->name('staff_index');

    Route::get('question_result', [TaskController::class, 'question_result'])
        ->name('question_result');
});

// PROFILE ROUTE (Usually only requires authentication)
Route::group(['middleware' => ['auth']], function () {
    Route::get('profile', [ProfileController::class, 'profile'])
        ->name('profile');
});

// ADMINISTRATION ROUTES

// --- API Route (Protected by auth middleware) ---
Route::group(['middleware' => ['auth', 'permission:admin_structure.index']], function () {
    // API Route for Dynamic Position Loading (used by user assignment forms)
    Route::get('admin/api/positions-by-unit', [AdminController::class, 'getPositionsByUnit'])
        ->name('admin.api.positions_by_unit');
});

// --- Main Admin Routes (Protected by auth middleware) ---
Route::group(['middleware' => ['auth']], function () {

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    // User Creation
    Route::get('admin_users/create', [AdminController::class, 'createUser'])
        ->middleware('permission:admin_users.create')
        ->name('admin_users.create');

    Route::post('admin_users/store', [AdminController::class, 'storeUser'])
        ->middleware('permission:admin_users.create')
        ->name('admin_users.store');

    // User Listing and Viewing
    Route::get('admin_users/index', [AdminController::class, 'indexUsers'])
        ->middleware('permission:admin_users.index')
        ->name('admin_users.index');

    Route::get('admin_users/show/{user}', [AdminController::class, 'showUser'])
        ->middleware('permission:admin_users.index')
        ->name('admin_users.show'); // Matches your preferred style

    // Permissions Assignment
    Route::get('admin_users/permissions/edit/{user}', [AdminController::class, 'editUserPermissions'])
        ->middleware('permission:admin_users.assign')
        ->name('admin_users.permissions.edit');

    Route::put('admin_users/permissions/{user}', [AdminController::class, 'updateUserPermissions'])
        ->middleware('permission:admin_users.assign')
        ->name('admin_users.permissions.update');

    // POSITION ASSIGNMENT (New Promotion/Transfer)
    Route::put('admin_users/{user}/position', [AdminController::class, 'updatePosition'])
        ->middleware('permission:admin_users.assign')
        ->name('admin_users.update_position');

    // 🌟 NEW: CORRECTION ROUTES 🌟

    // 1. Show Correction Form
    Route::get('admin_users/position/edit/{user}', [AdminController::class, 'editPositionRecord'])
        ->middleware('permission:admin_users.assign')
        ->name('admin_users.position.edit');

    // 2. Handle Correction Update
    Route::put('admin_users/{user}/position/correct-record', [AdminController::class, 'updateCorrection'])
        ->middleware('permission:admin_users.assign')
        ->name('admin_users.update_correction');

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('organizational_unit/index', [OrganizationalUnitController::class, 'index'])
        ->middleware('permission:organizational_unit.index')
        ->name('organizational_unit.index');

    // Unit Creation
    Route::post('organizational_unit/store', [OrganizationalUnitController::class, 'storeUnit'])
        ->middleware('permission:organizational_unit.store')
        ->name('organizational_unit.store');

        Route::get('admin_position/index', [AdminController::class, 'index'])
        ->middleware('permission:admin_position.index')
        ->name('admin_position.index');

    // Position Creation
    Route::post('admin_position/store', [AdminController::class, 'storePosition'])
        ->middleware('permission:admin_structure.index')
        ->name('admin.position.store');

    // Generic User Assignment 
    Route::post('admin_assign_user/store', [AdminController::class, 'assignUser'])
        ->middleware('permission:admin_structure.index')
        ->name('admin.assign.store');

    // Position Editing Routes (For the Position Model structure)

    Route::get('admin/structure/positions/{position}/edit', [AdminController::class, 'editPosition'])
        ->middleware('permission:admin_structure.index')
        ->name('admin_structure.positions.edit');

    Route::put('admin/structure/positions/{position}', [AdminController::class, 'updatePositionData'])
        ->middleware('permission:admin_structure.index')
        ->name('admin_structure.positions.update');
});


// PERMISSION MANAGEMENT
Route::group(['middleware' => ['auth']], function () {
    Route::get('permission/index', [PermissionController::class, 'index'])->name('permission.index');
});

require __DIR__ . '/auth.php';
