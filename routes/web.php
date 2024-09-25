<?php

use App\Http\Controllers\AttemptLeaderboardController;
use App\Http\Controllers\AverageAccuracyLeaderboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TotalScoreLeaderboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [HomeController::class, "index"])->name("home");
Route::get('/attemptleaderboard', [AttemptLeaderboardController::class, "index"])->name("home");
Route::get('/averageaccleaderboard', [AverageAccuracyLeaderboardController::class, "index"])->name("home");
Route::get('/totalscoreleaderboard', [TotalScoreLeaderboardController::class, "index"])->name("home");
