<?php

use App\Http\Controllers\AnnualCalendarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->prefix('calendar')->group(function () {

});