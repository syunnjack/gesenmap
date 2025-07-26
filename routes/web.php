<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameCenterController;

Route::get('/', [GameCenterController::class, 'index']);
Route::post('/store', [GameCenterController::class, 'store']);

