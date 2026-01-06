<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserPreferenceController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Preferences
    Route::get('/preferences', [UserPreferenceController::class, 'show']);
    Route::post('/preferences', [UserPreferenceController::class, 'store']);

    // Feed & Media
    Route::get('/feed', [FeedController::class, 'index']); // Global Feed
    Route::get('/swipe', [FeedController::class, 'swipe']); // Personalized Swipe
    Route::get('/media/{id}', [MediaController::class, 'show']);

    // Interactions
    Route::post('/interactions', [InteractionController::class, 'store']);

    // Watchlist
    Route::get('/watchlist', [WatchlistController::class, 'index']);
    Route::post('/watchlist', [WatchlistController::class, 'store']);
    Route::delete('/watchlist/{mediaId}', [WatchlistController::class, 'destroy']);
    Route::patch('/watchlist/{mediaId}/watched', [WatchlistController::class, 'markWatched']);

    // Comments
    Route::get('/media/{mediaId}/comments', [CommentController::class, 'index']);
    Route::post('/media/{mediaId}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
