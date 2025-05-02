<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TariffController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/filter', [PageController::class, 'filter'])->name('filter');


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {

    // Route::get('profiles', [ProfileController::class, 'index'])->name('profiles.index');

    // Public profiles
    Route::resource('profiles', ProfileController::class);

    Route::get('/chat', function () {
        return view('chat.index');
    })->name('chat.index');

    Route::get('/transaction', function () {
        return view('transactions.index');
    })->name('transaction.index');
    Route::get('/ads', [TariffController::class, 'index'])->name('advert.index');
    Route::post('/ads/activate', [TariffController::class, 'activate'])->name('advert.activate');
    Route::post('/ads/{id}/pause', [TariffController::class, 'pause'])->name('advert.pause');
    Route::post('/ads/{id}/resume', [TariffController::class, 'resume'])->name('advert.resume');
    Route::post('/ads/{id}/cancel', [TariffController::class, 'cancel'])->name('advert.cancel');

    Route::get('/profiles/create', [FormController::class, 'index'])->name('form.index');
});

// Route::get('/profiles', function () {
//     return view('profiles.index');
// })->name('profiles.index');

// Display individual profile page using the second-layout
Route::get('/profiles/view/{id}', function ($id) {
    return view('profiles.profile', ['id' => $id]);
})->name('profiles.view');

require __DIR__ . '/auth.php';



    // // Profile management (user settings)
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    
    // // Service routes (admin only)
    //     Route::resource('services', ServiceController::class)->except(['show']);
    
    // // Messaging
    // Route::resource('messages', MessageController::class);
    // Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.mark-read');
    
    // // Transactions
    // // Route::resource('transactions', TransactionController::class)->only(['index', 'show', 'create', 'store']);
    
    // // Advertisements
    // Route::resource('advertisements', AdvertisementController::class);
    
    // // Reviews
    // Route::resource('reviews', ReviewController::class)->except(['index', 'show']);