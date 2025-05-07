<?php
use Illuminate\Support\Facades\Route;

// User chat routes
// Route::middleware(['auth'])->group(function () {
//     Route::get('chat', [MessageController::class, 'index'])->name('chat.index');
//     Route::post('chat/send', [MessageController::class, 'sendMessage'])->name('chat.send');
//     Route::get('chat/messages', [MessageController::class, 'getMessages'])->name('chat.messages');
// });

// Admin chat routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('messenger', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('messenger.index');
    Route::post('chat/send', [\App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('chat/messages', [\App\Http\Controllers\Admin\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('chat/create-conversation', [\App\Http\Controllers\Admin\ChatController::class, 'createConversation'])->name('chat.create-conversation');
});