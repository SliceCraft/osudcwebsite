<?php

use App\Http\Controllers\AttemptLeaderboardController;
use App\Http\Controllers\AverageAccuracyLeaderboardController;
use App\Http\Controllers\AveragePlacementLeaderboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerSearchController;
use App\Http\Controllers\TotalScoreLeaderboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [HomeController::class, "index"])->name("home");
Route::get('/streakholders', [\App\Http\Controllers\StreakHolderController::class, 'index'])->name('streakholders');
Route::get('/leaderboard/{leaderboard}', [LeaderboardController::class, "index"])->name("leaderboard");
Route::get('/user/{username?}', [PlayerController::class, "index"])->name("userinfo");
Route::post('/user/{username?}', [PlayerController::class, "post"])->name("playersearchpost");
