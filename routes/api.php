<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserPreferenceController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [GoogleAuthController::class, 'loginWithGoogle']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/fcm-token', [AuthController::class, 'updateFcmToken']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'update']);
    Route::delete('/profile', [AuthController::class, 'destroy']);

    // Preferences
    Route::get('/preferences', [UserPreferenceController::class, 'show']);
    Route::post('/preferences', [UserPreferenceController::class, 'store']);

    // Feed & Media
    Route::get('/feed', [FeedController::class, 'index']); // Global Feed
    Route::get('/swipe', [FeedController::class, 'swipe']); // Personalized Swipe
    Route::get('/media/{id}', [MediaController::class, 'show']);
    Route::get('/media/{id}/videos', [MediaController::class, 'videos']);

    // Interactions
    Route::post('/interactions', [InteractionController::class, 'store']);

    // Watchlist
    Route::get('/watchlist', [WatchlistController::class, 'index']);
    Route::post('/watchlist', [WatchlistController::class, 'store']);
    Route::delete('/watchlist/{mediaId}', [WatchlistController::class, 'destroy']);
    Route::patch('/watchlist/{mediaId}/watched', [WatchlistController::class, 'markWatched']);

    // Following & Profile
    Route::post('/follow/{id}', [FollowController::class, 'follow']);
    Route::post('/unfollow/{id}', [FollowController::class, 'unfollow']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/{id}/badges', [UserController::class, 'badges']);
    Route::get('/users/{id}/following', [UserController::class, 'following']);
    Route::get('/users/{id}/followers', [UserController::class, 'followers']);
    Route::get('/media/{id}/watchers', [UserController::class, 'watchers']);

    // Comments
    Route::get('/media/{mediaId}/comments', [CommentController::class, 'index']);
    Route::post('/media/{mediaId}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // Chat
    Route::get('/conversations', [ChatController::class, 'index']);
    Route::get('/conversations/{id}/messages', [ChatController::class, 'messages']);
    Route::post('/messages', [ChatController::class, 'sendMessage']);
    Route::get('/buddies', [ChatController::class, 'buddies']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
