<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\IndicatorFeedbackController;
use App\Http\Controllers\ProjectController;
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

    Route::get('indicator/show/{indicator}', [IndicatorController::class, 'show'])
        ->name('indicator.show');

    Route::get('indicator/index', [IndicatorController::class, 'index'])
        ->name('indicator.index');

    Route::get('indicator/create', [IndicatorController::class, 'create'])
        ->name('indicator.create');

    Route::post('indicator/store', [IndicatorController::class, 'store'])
        ->name('indicator.store');
});

Route::get('projects/index', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('projects/show', [ProjectController::class, 'show'])
    ->name('projects.show');

Route::get('projects/steps/show', [ProjectController::class, 'stepsShow'])
    ->name('projects.steps.show');

Route::view('report', 'report')
    ->middleware(['auth'])
    ->name('report');

Route::view('achievements', 'achievements')
    ->middleware(['auth'])
    ->name('achievements');

Route::view('task/index', 'task.index')
    ->middleware(['auth'])
    ->name('task.index');

Route::view('indicator/contribute', 'indicator.contribute')
    ->middleware(['auth'])
    ->name('indicator.contribute');

Route::view('indicator/contribute/details', 'indicator.contribute.details')
    ->middleware(['auth'])
    ->name('indicator.contribute.details');


Route::view('my-tasks', 'my-tasks')
    ->middleware(['auth'])
    ->name('my-tasks');

// Profile
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
Route::post('logout', [DashboardController::class, 'logout'])
    ->middleware(['auth'])
    ->name('logout');
