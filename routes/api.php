<?php

use App\Http\Controllers\Api\RoomsController;
use Illuminate\Support\Facades\Route;

Route::get('rooms', [RoomsController::class, 'index'])->name('rooms');
