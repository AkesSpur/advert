<?php


use App\Http\Controllers\CommentController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VerificationController;
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

require __DIR__ . '/auth.php';


Route::get('/', [PageController::class, 'index'])->name('home');

// Directory-style filter routes
Route::get('/service/{slug}', [PageController::class, 'index'])->name('home.service');
Route::get('/metro/{slug}', [PageController::class, 'index'])->name('home.metro');
Route::get('/price/{slug}', [PageController::class, 'index'])->name('home.price');
Route::get('/age/{slug}', [PageController::class, 'index'])->name('home.age');

Route::get('/profiles/click/{id}', [PageController::class, 'profileClick'])->name('profiles.clicks');
Route::get('/profiles/{id}', [PageController::class, 'show'])->name('profiles.view');

// Comment and Review routes (no auth required)
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {

    // Route::get('profiles', [ProfileController::class, 'index'])->name('profiles.index');

    // Public profiles
    Route::resource('profiles', ProfileController::class);

// Profile archive routes
Route::get('profiles/{id}/archive', [ProfileController::class, 'archive'])->name('profiles.archive');
Route::post('profiles/{id}/restore', [ProfileController::class, 'restore'])->name('profiles.restore');
    
    // Profile verification routes
    Route::get('profiles/{id}/verification', [VerificationController::class, 'showVerificationForm'])->name('profiles.verification.form');
    Route::post('profiles/{id}/verification', [VerificationController::class, 'submitVerification'])->name('profiles.verification.submit');
    Route::get('profiles/{id}/verification/reapply', [VerificationController::class, 'reapplyVerification'])->name('profiles.verification.reapply');

    Route::get('chat', [MessageController::class, 'index'])->name('chat.index');
    Route::post('chat/send', [MessageController::class, 'sendMessage'])->name('chat.send');
    Route::get('chat/messages', [MessageController::class, 'getMessages'])->name('chat.messages');

    Route::get('/transaction', [TransactionController::class, 'index'] )->name('transaction.index');
    Route::get('/ads', [TariffController::class, 'index'])->name('advert.index');
    Route::post('/ads/activate', [TariffController::class, 'activate'])->name('advert.activate');
    Route::post('/ads/{id}/pause', [TariffController::class, 'pause'])->name('advert.pause');
    Route::post('/ads/{id}/resume', [TariffController::class, 'resume'])->name('advert.resume');
    Route::post('/ads/{id}/cancel', [TariffController::class, 'cancel'])->name('advert.cancel');

    Route::get('/profiles/create', [FormController::class, 'index'])->name('form.index');

    Route::post('/profile/{id}/toggle-like', [LikeController::class, 'toggle'])->name('profile.toggleLike');
    Route::get('/my-likes', [LikeController::class, 'likedProfiles'])->name('profile.likedProfiles');

});



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