<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/privacy', function () {
    return view('privacy');
});

Route::get('/terms', function () {
    return view('terms');
});

// Admin Routes
use App\Http\Controllers\AdminController;

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
        Route::post('/users/{user}/toggle-block', [AdminController::class, 'toggleBlock'])->name('admin.users.toggle-block');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::post('/users/{user}/change-password', [AdminController::class, 'changeUserPassword'])->name('admin.users.change-password');
        
        // Media
        Route::get('/media', [AdminController::class, 'media'])->name('admin.media.index');
        Route::post('/media/sync', [AdminController::class, 'syncTMDB'])->name('admin.media.sync');
        
        // Notifications
        Route::post('/notifications/send', [AdminController::class, 'sendPush'])->name('admin.notifications.send');
    });
});
