<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameCenterController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\VoteController;

Route::get('/', [GameCenterController::class, 'index'])->name('home');
Route::post('/store', [GameCenterController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('game-centers.store');
// 都道府県から探す・店舗ごとのページ
Route::get('/area', [GameCenterController::class, 'areas'])->name('areas.index');
Route::get('/area/{prefectureSlug}', [GameCenterController::class, 'area'])
    ->whereAlpha('prefectureSlug')
    ->name('areas.show');
Route::get('/g/{slug}', [GameCenterController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('game-centers.show');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::view('/about', 'about')->name('about');

Route::post('/game-centers/{gameCenter}/vote', [VoteController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('game-centers.vote');

Route::post('/game-centers/{gameCenter}/reviews', [ReviewController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('reviews.store');
