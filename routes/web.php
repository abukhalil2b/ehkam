<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityWorkflowController;
use App\Http\Controllers\Admin\WorkflowController;
use App\Http\Controllers\IndicatorWorkflowController;
use App\Http\Controllers\Admin\AdminProfilePermissionController;
use App\Http\Controllers\Admin\AdminAimController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\AssessmentQuestionController;
use App\Http\Controllers\AssessmentResultController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\FinanceFormController;
use App\Http\Controllers\Admin\FinanceNeedController;
use App\Http\Controllers\Admin\AdminAimSectorFeedbackController;
use App\Http\Controllers\AnnualCalendarController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\AimSectorFeedbackController;
use App\Http\Controllers\MeetingMinuteController;
use App\Http\Controllers\MissionTaskController;
use App\Http\Controllers\OrgUnitController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\StepWorkflowController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarDelegationController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\WorkshopController;
use App\Http\Controllers\Admin\CompetitionController as AdminCompetitionController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Participant\CompetitionController as ParticipantCompetitionController;
use App\Http\Controllers\SwotController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome2')->name('home');

Route::match(['get', 'post'], 'workshow_attendance_register', [WorkshopController::class, 'attendance_register'])
    ->name('workshow_attendance_register');

Route::get('explain_assessment_to_audience', [AssessmentQuestionController::class, 'explain_assessment_to_audience'])
    ->name('explain_assessment_to_audience');

// Dashboard - Usually just requires authentication
Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');
});




// INDICATOR ROUTES
Route::group(['middleware' => ['auth']], function () {

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

    Route::post('indicator/target/store/{indicator}', [IndicatorController::class, 'storeTarget'])
        ->middleware('permission:indicator.index')
        ->name('indicator.target.store');

    Route::post('indicator/achieved/store/{indicator}', [IndicatorController::class, 'storeAchieved'])
        ->middleware('permission:indicator.index')
        ->name('indicator.achieved.store');


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

    // مسار جلب الوحدات التابعة (للـ Ajax)
    Route::get('/api/units/{parentId}/children', [ProjectController::class, 'getUnitChildren']);
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('step/index/{project}/{activity?}', [StepController::class, 'index'])
        ->name('step.index');

    Route::post('step/store/{project}', [StepController::class, 'store'])->name('step.store');

    Route::get('step/show/{step}', [StepController::class, 'show'])
        ->name('step.show');

    Route::post('step/{step}/upload_evidence', [StepController::class, 'uploadEvidence'])->name('step.uploadEvidence');

    Route::get('step/edit/{step}', [StepController::class, 'edit'])->name('step.edit');
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
// public for attendance registration form
Route::get('meeting_minute/attendance_registration_form/{token}', [MeetingMinuteController::class, 'attendanceRegistrationForm'])
    ->name('meeting_minute.attendance_registration_form');
Route::post('meeting_minute/attendance_registration_store/{token}', [MeetingMinuteController::class, 'attendanceRegistrationStore'])
    ->name('meeting_minute.attendance_registration_store');


Route::group(['middleware' => ['auth']], function () {

    Route::get('workshop/index', [WorkshopController::class, 'index'])
        ->middleware('permission:workshop.show')
        ->name('workshop.index');

    Route::get('workshop/create', [WorkshopController::class, 'create'])
        ->middleware('permission:workshop.create')
        ->name('workshop.create');

    Route::post('workshop/store', [WorkshopController::class, 'store'])
        ->middleware('permission:workshop.create')
        ->name('workshop.store');

    Route::post('workshop/replicate/{workshop}', [WorkshopController::class, 'replicate'])
        ->middleware('permission:workshop.create')
        ->name('workshop.replicate');

    Route::get('workshop/show/{workshop}', [WorkshopController::class, 'show'])
        ->middleware('permission:workshop.show')
        ->name('workshop.show');

    Route::get('workshop/edit/{workshop}', [WorkshopController::class, 'edit'])
        ->middleware('permission:workshop.edit')
        ->name('workshop.edit');

    Route::put('workshop/update/{workshop}', [WorkshopController::class, 'update'])
        ->middleware('permission:workshop.edit')
        ->name('workshop.update');

    Route::get('workshop/edit_status/{workshop}', [WorkshopController::class, 'editStatus'])
        ->middleware('permission:workshop.edit')
        ->name('workshop.edit_status');

    Route::put('workshop/update_status/{workshop}', [WorkshopController::class, 'updateStatus'])
        ->middleware('permission:workshop.edit')
        ->name('workshop.update_status');

    Route::get('workshop/attendance_report', [WorkshopController::class, 'attendanceReport'])
        ->middleware('permission:workshop.show')
        ->name('workshop.attendance_report');

    Route::delete('workshop/destroy/{workshop}', [WorkshopController::class, 'destroy'])
        ->middleware('permission:workshop.delete')
        ->name('workshop.destroy');
});

Route::group(['middleware' => ['auth']], function () {
    Route::post('activity/workflow/{activity}', [ActivityWorkflowController::class, 'transition'])
        ->name('activity.workflow.transition');

    Route::post('indicator/workflow/{indicator}', [IndicatorWorkflowController::class, 'transition'])
        ->name('indicator.workflow.transition');
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('qr/index', [QrCodeController::class, 'index'])
        ->middleware('permission:qr.show')
        ->name('qr.index');

    Route::post('qr/store', [QrCodeController::class, 'store'])
        ->middleware('permission:qr.create')
        ->name('qr.store');

    Route::get('qr/show/{qr}', [QrCodeController::class, 'show'])
        ->middleware('permission:qr.show')
        ->name('qr.show');

    Route::delete('qr/destroy/{qr}', [QrCodeController::class, 'destroy'])
        ->middleware('permission:qr.delete')
        ->name('qr.destroy');
});


Route::group(['middleware' => ['auth']], function () {
    // Activity
    Route::get('timeline/index', [TimelineController::class, 'index'])
        ->middleware('permission:timeline.index')
        ->name('timeline.index');
    Route::get('timeline/show/{calendarEvent}', [TimelineController::class, 'show'])
        ->middleware('permission:timeline.show')
        ->name('timeline.show');
});

Route::group(['middleware' => ['auth']], function () {

    // Calendar Delegations
    Route::get('calendar/delegations', [CalendarDelegationController::class, 'index'])->name('delegations.index');
    Route::post('calendar/delegations', [CalendarDelegationController::class, 'store'])->name('delegations.store');
    Route::delete('calendar/delegations/{delegation}', [CalendarDelegationController::class, 'destroy'])->name('delegations.destroy');

    // Calendar Routes
    Route::get('calendar/index', [AnnualCalendarController::class, 'index'])->name('calendar.index');
    Route::get('calendar/show', [AnnualCalendarController::class, 'show'])->name('calendar.show');
    Route::get('calendar/create', [AnnualCalendarController::class, 'create'])->name('calendar.create');
    Route::post('calendar/store', [AnnualCalendarController::class, 'store'])->name('calendar.store');
    Route::get('calendar/edit/{calendarEvent}', [AnnualCalendarController::class, 'edit'])->name('calendar.edit');
    Route::put('calendar/update/{calendarEvent}', [AnnualCalendarController::class, 'update'])->name('calendar.update');
    Route::delete('calendar/destroy/{calendarEvent}', [AnnualCalendarController::class, 'destroy'])->name('calendar.destroy');
    Route::get('calendar/settings', [AnnualCalendarController::class, 'settings'])->name('calendar.settings');

    // clear cache
    Route::post('calendar/refresh', [AnnualCalendarController::class, 'refreshCache'])->name('calendar.refresh');


    // AJAX Event Loading
    Route::get('calendar/events', [AnnualCalendarController::class, 'loadEvents']);

    // Drag & Drop Event Move
    Route::patch('calendar/{calendarEvent}/move', [AnnualCalendarController::class, 'moveEvent']);

    // Department View
    Route::get('calendar/department/{orgUnit}', [AnnualCalendarController::class, 'department'])->name('calendar.department');
});

Route::get('/notifications/read-all', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.readAll');


// ACTIVITY & ASSESSMENT ROUTES
Route::group(['middleware' => ['auth']], function () {
    // Activity
    Route::get('activity/index/{year}', [ActivityController::class, 'index'])
        ->middleware('permission:activity.index')
        ->name('activity.index');

    Route::get('activity/create/{project}', [ActivityController::class, 'create'])
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

Route::get('statistic/index', [StatisticController::class, 'index'])
    ->middleware('permission:statistic.index')
    ->name('statistic.index');

Route::get('questionnaire/{questionnaire}/share', [QuestionnaireController::class, 'shareLink'])
    ->name('questionnaire.share_link');

Route::get('statistic/quran/{id}', [StatisticController::class, 'quran'])
    ->middleware('permission:statistic.index')
    ->name('statistic.quran');

Route::get('statistic/zakah/{id}', [StatisticController::class, 'zakah'])
    ->middleware('permission:statistic.index')
    ->name('statistic.zakah');

Route::group(['middleware' => ['auth']], function () {
    Route::get('admin/finance_form/index', [FinanceFormController::class, 'index'])
        // ->middleware('permission:finance_form.index')
        ->name('admin.finance_form.index');

    Route::post('admin/finance_form/store', [FinanceFormController::class, 'store'])
        // ->middleware('permission:finance_form.store')
        ->name('admin.finance_form.store');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('admin/finance_need/index', [FinanceNeedController::class, 'index'])
        // ->middleware('permission:finance_need.index')
        ->name('admin.finance_need.index');

    Route::post('admin/finance_need/store', [FinanceNeedController::class, 'store'])
        // ->middleware('permission:finance_need.store')
        ->name('admin.finance_need.store');
});

// QUESTIONNAIRE ROUTES
Route::group(['middleware' => ['auth']], function () {
    Route::get('questionnaire/index', [QuestionnaireController::class, 'index'])
        ->middleware('permission:questionnaire.index')
        ->name('questionnaire.index');

    // Viewing and management
    Route::get('questionnaire/show/{questionnaire}', [QuestionnaireController::class, 'show'])
        ->middleware('permission:questionnaire.index') // Viewing is part of index
        ->name('questionnaire.show');

    Route::get('questionnaire/statistics/{questionnaire}', [QuestionnaireController::class, 'statistics'])
        ->middleware('permission:questionnaire.index')
        ->name('questionnaire.statistics');

    Route::get('questionnaire/create', [QuestionnaireController::class, 'create'])
        ->middleware('permission:questionnaire.create')
        ->name('questionnaire.create');

    Route::post('questionnaire/store', [QuestionnaireController::class, 'store'])
        ->middleware('permission:questionnaire.create')
        ->name('questionnaire.store');

    Route::get('questionnaire/duplicate/{questionnaire}', [QuestionnaireController::class, 'duplicate'])
        ->middleware('permission:questionnaire.create') // Duplicating is like creating
        ->name('questionnaire.duplicate');

    // Edit/Update Permissions
    Route::get('questionnaire/edit/{questionnaire}', [QuestionnaireController::class, 'edit'])
        ->middleware('permission:questionnaire.edit')
        ->name('questionnaire.edit');

    Route::put('questionnaire/update/{questionnaire}', [QuestionnaireController::class, 'update'])
        ->middleware('permission:questionnaire.edit')
        ->name('questionnaire.update');

    Route::get('questionnaire/question_edit/{questionnaire}', [QuestionnaireController::class, 'question_edit'])
        ->middleware('permission:questionnaire.edit')
        ->name('questionnaire.question_edit');

    Route::put('questionnaire/question_update/{questionnaire}', [QuestionnaireController::class, 'question_update'])
        ->middleware('permission:questionnaire.edit')
        ->name('questionnaire.question_update');

    Route::post('questionnaire/registerd_only_submit/{questionnaire}', [QuestionnaireController::class, 'registerd_only_submit'])
        ->name('questionnaire.registerd_only_submit'); // Usually open to all authenticated users

    Route::delete('questionnaire/delete/{questionnaire}', [QuestionnaireController::class, 'delete'])
        ->middleware('permission:questionnaire.delete')
        ->name('questionnaire.delete');

    Route::get('questionnaire/answer_index/{questionnaire}', [QuestionnaireController::class, 'answer_index'])
        ->middleware('permission:questionnaire.answer_index')
        ->name('questionnaire.answer_index');

    Route::get('questionnaire/answer_show/{answer}', [QuestionnaireController::class, 'answerShow'])
        ->middleware('permission:questionnaire.answer_index')
        ->name('questionnaire.answer_show');

    Route::put('questionnaire/answer_update/{answer}', [QuestionnaireController::class, 'updateAnswer'])
        ->middleware('permission:questionnaire.answer_edit')
        ->name('questionnaire.answer_update');

    Route::get('questionnaire/export/{questionnaire}', [QuestionnaireController::class, 'export'])
        ->middleware('permission:questionnaire.export')
        ->name('questionnaire.export');



    Route::get('questionnaire/registered_take/{questionnaire}', [QuestionnaireController::class, 'registeredTake'])
        ->name('questionnaire.registered_take'); // Usually open to all authenticated users

    Route::get('questionnaire/public_result/{questionnaire}', [QuestionnaireController::class, 'showPublicResults'])
        ->name('questionnaire.public_result');
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

Route::middleware(['auth'])->group(function () {

    Route::get('mission/index', [MissionTaskController::class, 'missionIndex'])
        ->name('mission.index');

    Route::post('mission/store', [MissionTaskController::class, 'missionStore'])
        ->name('mission.store');

    Route::post('mission/update', [MissionTaskController::class, 'missionUpdate'])
        ->name('mission.update');

    Route::get('missions/task/{mission}', [MissionTaskController::class, 'show'])
        ->name('missions.task.show');
    // إنشاء مهمة
    Route::post('missions/task/{mission}', [MissionTaskController::class, 'store'])
        ->name('missions.task.store');

    // تحديث مهمة
    Route::put('missions/task/{mission}/{task}', [MissionTaskController::class, 'update'])
        ->name('missions.task.update');

    // إعادة ترتيب (Drag & Drop)
    Route::patch('missions/task/{mission}/{task}/reorder', [MissionTaskController::class, 'reorder'])
        ->name('missions.task.reorder');

    // تحديث الحالة
    Route::patch('missions/task/{mission}/{task}/status', [MissionTaskController::class, 'updateStatus'])
        ->name('missions.task.status');

    // حذف مهمة
    Route::delete('missions/task/{mission}/{task}', [MissionTaskController::class, 'destroy'])
        ->name('missions.task.destroy');
});

// PROFILE ROUTE (Usually only requires authentication)
Route::group(['middleware' => ['auth']], function () {
    // Profile Switching
    Route::get('/profile/switch/{id}', [App\Http\Controllers\ProfileSwitcherController::class, 'switch'])->name('profile.switch');
    Route::get('/profile/reset', [App\Http\Controllers\ProfileSwitcherController::class, 'reset'])->name('profile.reset');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // Notifications
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/click', [App\Http\Controllers\NotificationController::class, 'readAndRedirect'])->name('notifications.readAndRedirect');
    Route::get('/notifications/mark-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::get('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/delete-all', [App\Http\Controllers\NotificationController::class, 'deleteAll'])->name('notifications.delete_all');
});


// --- Main Admin Routes (Protected by auth middleware) ---
Route::group(['middleware' => ['auth']], function () {

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('admin_users/create_for_sector', [AdminController::class, 'createUserForSector'])
        ->middleware('permission:admin_users.create')
        ->name('admin_users.create_for_sector');

    Route::get('admin_users/link_user_with_sector_create/{user}', [AdminController::class, 'linkUserWithSectorCreate'])
        ->middleware('permission:admin_users.create')
        ->name('admin_users.link_user_with_sector_create');

    Route::post('admin_users/link_user_with_sector_store/{user}', [AdminController::class, 'linkUserWithSectorStore'])
        ->middleware('permission:admin_users.create')
        ->name('admin_users.link_user_with_sector_store');

    Route::post('admin_users/store_for_sector', [AdminController::class, 'storeUserForSector'])
        ->middleware('permission:admin_users.create')
        ->name('admin_users.store_for_sector');

    // User Creation
    Route::get('admin_users/create', [AdminController::class, 'createUser'])
        ->middleware('permission:admin_users.create')
        ->name('admin_users.create');

    // Admin Notifications
    Route::get('admin/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])
        ->middleware('permission:admin.workflow.index') // Assuming admin workflow permission is sufficient or add specific one
        ->name('admin.notifications.index');

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


    Route::group(['middleware' => ['auth']], function () {
        Route::get('admin_setting/indicator/index/{year}', [AdminSettingController::class, 'indicatorIndex'])
            ->middleware('permission:indicator.index')
            ->name('admin_setting.indicator.index');

        Route::get('admin_setting/project/index/{year}', [AdminSettingController::class, 'projectIndex'])
            ->middleware('permission:project.index')
            ->name('admin_setting.project.index');

        Route::post('admin_setting/copy-year/{year}', [AdminSettingController::class, 'copyYear'])
            ->middleware('permission:indicator.create')
            ->name('admin_setting.copy_year');
    });

    // ADMINISTRATION ROUTES

    // --- API Route (Protected by auth middleware) ---
    Route::group(['middleware' => ['auth', 'permission:admin_structure.index']], function () {
        // API Route for Dynamic Position Loading (used by user assignment forms)
        Route::get('admin/api/positions-by-unit', [AdminController::class, 'getPositionsByUnit'])
            ->name('admin.api.positions_by_unit');
    });

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('org_unit/index', [OrgUnitController::class, 'index'])
        ->middleware('permission:org_unit.index')
        ->name('org_unit.index');

    Route::get('org_unit/create', [OrgUnitController::class, 'create'])
        ->middleware('permission:org_unit.create')
        ->name('org_unit.create');


    // Unit Creation
    Route::post('org_unit/store', [OrgUnitController::class, 'storeUnit'])
        ->middleware('permission:org_unit.create')
        ->name('org_unit.store');

    Route::get('org_unit/edit/{orgUnit}', [OrgUnitController::class, 'edit'])
        ->middleware('permission:org_unit.edit')
        ->name('org_unit.edit');

    Route::put('org_unit/update/{orgUnit}', [OrgUnitController::class, 'update'])
        ->middleware('permission:org_unit.edit')
        ->name('org_unit.update');

    // Org Unit Position Management
    Route::post('org_unit/positions/store', [OrgUnitController::class, 'addPosition'])
        ->middleware('permission:org_unit.edit')
        ->name('org_unit.positions.store');

    Route::delete('org_unit/positions/destroy', [OrgUnitController::class, 'removePosition'])
        ->middleware('permission:org_unit.edit')
        ->name('org_unit.positions.destroy');

    // Step Workflows
    Route::post('/steps/{step}/workflow', [StepWorkflowController::class, 'transition'])->name('step.workflow.transition');

    // Admin Workflows Dashboard
    Route::get('/admin/workflows', [WorkflowController::class, 'index'])
        ->middleware('permission:admin.workflow.index')
        ->name('admin.workflows.index');

    // POSITIONS MANAGEMENT (Refactored)
    Route::resource('positions', PositionController::class)->middleware('permission:admin_position.index');

    Route::get('admin_position/index', [AdminController::class, 'index'])
        ->middleware('permission:admin_position.index')
        ->name('admin_position.index');

    Route::get('admin_position/create', [AdminController::class, 'create'])
        ->middleware('permission:admin_position.create')
        ->name('admin_position.create');

    // Position Creation
    // Route::post('admin_position/store', [AdminController::class, 'storePosition'])
    //     ->middleware('permission:admin_structure.index')
    //     ->name('admin.position.store');

    // Generic User Assignment 
    Route::post('admin_assign_user/store', [AdminController::class, 'assignUser'])
        ->middleware('permission:admin_structure.index')
        ->name('admin.assign.store');

    // Position Editing Routes (For the Position Model structure)

    // Route::get('admin/structure/positions/{position}/edit', [AdminController::class, 'editPosition'])
    //     ->middleware('permission:admin_structure.index')
    //     ->name('admin_structure.positions.edit');

    // Route::put('admin/structure/positions/{position}', [AdminController::class, 'updatePositionData'])
    //     ->middleware('permission:admin_structure.index')
    //     ->name('admin_structure.positions.update');

    Route::get('missing_units_assignment_attach_unit', [StructureController::class, 'missingUnitsIndex'])
        ->name('missing_units_assignment');

    Route::post('missing_units_assignment', [StructureController::class, 'attachUnitToPosition'])
        ->name('admin.structure.attach_unit');
});

Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['permission:permission.index']], function () {
        // Admin Profile Management
        Route::get('admin/profiles', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('admin.profiles.index');
        Route::get('admin/profiles/create', [\App\Http\Controllers\Admin\ProfileController::class, 'create'])->name('admin.profiles.create');
        Route::post('admin/profiles', [\App\Http\Controllers\Admin\ProfileController::class, 'store'])->name('admin.profiles.store');

        Route::get('profile_permission/{profile}', [AdminProfilePermissionController::class, 'index'])->name('profile.permission');
        Route::post('profile_permission/{profile}', [AdminProfilePermissionController::class, 'profilePermissionUpdate'])->name('profile.permission.update');
    });

    Route::get('admin/aim/index', [AdminAimController::class, 'index'])
        ->name('admin.aim.index');

    Route::post('admin/aim/store', [AdminAimController::class, 'store'])
        ->name('admin.aim.store');

    Route::get('admin/aim/{aim}/edit', [AdminAimController::class, 'edit'])
        ->name('admin.aim.edit');

    Route::put('admin/aim/{aim}', [AdminAimController::class, 'update'])
        ->name('admin.aim.update');

    Route::delete('admin/aim/{aim}', [AdminAimController::class, 'destroy'])
        ->name('admin.aim.destroy');
});


Route::group(['middleware' => ['auth']], function () {

    Route::get('admin/aim_sector_feedback/index/{current_year}', [AdminAimSectorFeedbackController::class, 'index'])
        ->name('admin.aim_sector_feedback.index');

    Route::get('admin/aim_sector_feedback/show/{aim}/{sector}', [AdminAimSectorFeedbackController::class, 'show'])
        ->name('admin.aim_sector_feedback.show');
});


Route::group(['middleware' => ['auth']], function () {

    Route::get('aim_sector_feedback/index/{aim}', [AimSectorFeedbackController::class, 'index'])
        ->name('aim_sector_feedback.index');

    // SHOW – details of a single feedback
    Route::get('aim_sector_feedback/show/{feedback}', [AimSectorFeedbackController::class, 'show'])
        ->name('aim_sector_feedback.show');

    // CREATE
    Route::get('aim_sector_feedback/create/{aim}/{current_year}', [AimSectorFeedbackController::class, 'create'])
        ->name('aim_sector_feedback.create');

    // STORE
    Route::post('aim_sector_feedback/store/{aim}', [AimSectorFeedbackController::class, 'store'])
        ->name('aim_sector_feedback.store');
});



// PERMISSION MANAGEMENT
Route::group(['middleware' => ['auth']], function () {
    Route::get('permission/index', [PermissionController::class, 'index'])->name('permission.index');
});


// Admin Routes (add your auth middleware)
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {

    // Competition Management
    Route::resource('competitions', AdminCompetitionController::class);

    // Question Management
    Route::post('competitions/{competition}/questions', [AdminCompetitionController::class, 'storeQuestion'])
        ->name('competitions.questions.store');
    Route::delete('questions/{question}', [AdminCompetitionController::class, 'destroyQuestion'])
        ->name('questions.destroy');

    // Competition Control
    Route::post('competitions/{competition}/start', [AdminCompetitionController::class, 'start'])
        ->name('competitions.start');
    Route::post('competitions/{competition}/push-question/{question}', [AdminCompetitionController::class, 'pushQuestion'])
        ->name('competitions.push-question');
    Route::post('competitions/{competition}/close-question', [AdminCompetitionController::class, 'closeQuestion'])
        ->name('competitions.close-question');
    Route::post('competitions/{competition}/finish', [AdminCompetitionController::class, 'finish'])
        ->name('competitions.finish');

    // Live Data
    Route::get('competitions/{competition}/live', [AdminCompetitionController::class, 'liveData'])
        ->name('competitions.live');
    Route::get('competitions/{competition}/results', [AdminCompetitionController::class, 'results'])
        ->name('competitions.results');
});

// Participant Routes (Public)
Route::prefix('compete')->name('participant.competition.')->group(function () {
    Route::get('join/{code}', [ParticipantCompetitionController::class, 'join'])
        ->name('join');
    Route::post('register/{code}', [ParticipantCompetitionController::class, 'register'])
        ->name('register');
    Route::get('wait/{competition}', [ParticipantCompetitionController::class, 'wait'])
        ->name('wait');
    Route::get('play/{competition}', [ParticipantCompetitionController::class, 'play'])
        ->name('play');
    Route::post('answer/{competition}', [ParticipantCompetitionController::class, 'submitAnswer'])
        ->name('answer');
    Route::get('live/{competition}', [ParticipantCompetitionController::class, 'liveData'])
        ->name('live');
});

// Admin routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/swot', [SwotController::class, 'index'])->name('swot.index');
    Route::get('/swot/create', [SwotController::class, 'create'])->name('swot.create');
    Route::post('/swot', [SwotController::class, 'store'])->name('swot.store');
    Route::get('/swot/admin/{id}', [SwotController::class, 'admin'])->name('swot.admin');
    Route::get('/swot/admin/{id}/display', [SwotController::class, 'display'])
        ->name('swot.display');
    Route::get('/swot/admin/{id}/finalize', [SwotController::class, 'finalize'])->name('swot.finalize');
    Route::post('/swot/admin/{id}/finalize', [SwotController::class, 'finalizeSave'])->name('swot.finalize.save');
    Route::get('/swot/admin/{id}/export-excel', [SwotController::class, 'exportExcel'])->name('swot.export.excel');

    //API to update board type
    Route::patch('/swot/admin/board/{board}/move', [SwotController::class, 'moveBoardItem'])->name('swot.board.move');
    Route::patch('/swot/admin/board/{board}/update-content', [SwotController::class, 'updateBoardContent'])->name('swot.board.update');
});

// Public routes (no authentication required)
Route::get('/swot/board/{token}', [SwotController::class, 'show'])->name('swot.public');
Route::post('/swot/board/{token}/init', [SwotController::class, 'initSession'])->name('swot.init');
Route::post('/swot/board/{token}/add', [SwotController::class, 'addItem'])->name('swot.add');
Route::get('/swot/board/{token}/items', [SwotController::class, 'getItems'])->name('swot.items');

require __DIR__ . '/auth.php';
