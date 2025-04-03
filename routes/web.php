<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Dashboard
Route::view('dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

Route::view('projects/index', 'projects.index')
    ->middleware(['auth'])
    ->name('projects.index');

Route::view('projects/show', 'projects.show')
    ->middleware(['auth'])
    ->name('projects.show');

Route::view('report', 'report')
    ->middleware(['auth'])
    ->name('report');

Route::view('achievements', 'achievements')
    ->middleware(['auth'])
    ->name('achievements');

Route::view('adhoc', 'adhoc')
    ->middleware(['auth'])
    ->name('adhoc');

Route::view('indicator/index', 'indicator.index')
    ->middleware(['auth'])
    ->name('indicator.index');

    Route::view('indicator/show', 'indicator.show')
    ->middleware(['auth'])
    ->name('indicator.show');

Route::view('my-tasks', 'my-tasks')
    ->middleware(['auth'])
    ->name('my-tasks');

// Profile
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
