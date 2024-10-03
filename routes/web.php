<?php

use App\Http\Controllers\AttemptLeaderboardController;
use App\Http\Controllers\AverageAccuracyLeaderboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerSearchController;
use App\Http\Controllers\TotalScoreLeaderboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [HomeController::class, "index"])->name("home");
Route::get('/attemptleaderboard', [AttemptLeaderboardController::class, "index"])->name("attemptlb");
Route::get('/averageaccleaderboard', [AverageAccuracyLeaderboardController::class, "index"])->name("acclb");
Route::get('/totalscoreleaderboard', [TotalScoreLeaderboardController::class, "index"])->name("totalscorelb");
Route::get('/user/{username?}', [PlayerController::class, "index"])->name("userinfo");
Route::post('/user/{username?}', [PlayerController::class, "post"])->name("playersearchpost");
