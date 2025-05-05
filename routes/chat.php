<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

// User chat routes
Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [MessageController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [MessageController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/messages', [MessageController::class, 'getMessages'])->name('chat.messages');
});

// Admin chat routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/messenger', [MessageController::class, 'index'])->name('messenger.index');
    Route::post('/messenger/send', [MessageController::class, 'sendMessage'])->name('send-message');
    Route::get('/messenger/messages', [MessageController::class, 'getMessages'])->name('get-messages');
});