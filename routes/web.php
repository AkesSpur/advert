<?php


use App\Http\Controllers\CommentController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Payment\WebMoneyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingsController;
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

// WebMoney Payment Routes
Route::group(['prefix' => 'payment/webmoney', 'as' => 'payment.webmoney.'], function () {
    Route::post('pay', [WebMoneyController::class, 'pay'])->name('pay')->middleware('auth');
    Route::post('result', [WebMoneyController::class, 'result'])->name('result'); // WebMoney sends POST here
    Route::get('success', [WebMoneyController::class, 'success'])->name('success')->middleware('auth'); // User redirected here on success
    Route::get('fail', [WebMoneyController::class, 'fail'])->name('fail')->middleware('auth'); // User redirected here on failure
});





Route::get('/', [PageController::class, 'index'])->name('home');

// Directory-style filter routes
Route::get('/service/{slug}', [PageController::class, 'index'])->name('home.service');
Route::get('/metro/{slug}', [PageController::class, 'index'])->name('home.metro');
Route::get('/price/{slug}', [PageController::class, 'index'])->name('home.price');
Route::get('/age/{slug}', [PageController::class, 'index'])->name('home.age');
Route::get('/hair-color/{slug}', [PageController::class, 'index'])->name('home.hair_color');
Route::get('/height/{slug}', [PageController::class, 'index'])->name('home.height');
Route::get('/weight/{slug}', [PageController::class, 'index'])->name('home.weight');
Route::get('/breast-size/{slug}', [PageController::class, 'index'])->name('home.breast-size');
Route::get('/category/{slug}', [PageController::class, 'filterByCustomCategory'])->name('filter.custom-category');
Route::get('/neighborhood/{slug}', [PageController::class, 'index'])->name('home.neighborhood');
// profiles
Route::get('/profiles/click/{id}', [PageController::class, 'profileClick'])->name('profiles.clicks');
Route::get('/profiles/{slug}-{id}', [PageController::class, 'show'])->name('profiles.view')->where(['slug' => '[a-zA-Z0-9\-]+', 'id' => '[0-9]+']);
// likes
Route::post('/profile/{id}/toggle-like', [LikeController::class, 'toggle'])->name('profile.toggleLike');
Route::get('/my-likes', [LikeController::class, 'likedProfiles'])->name('profile.likedProfiles');


// Comment and Review routes (no auth required)
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'user', 'as' => 'user.'], function () {

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
    Route::post('/ads/update-priority', [TariffController::class, 'updatePriority'])->name('advert.update-priority');
    Route::get('/profiles/create', [FormController::class, 'index'])->name('form.index');

    // Profile management (user settings)
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings', [SettingsController::class, 'destroy'])->name('settings.destroy');

});

// Route::group(['middleware' => ['auth', 'verified', 'role:admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
//     // Existing admin routes here (if any)

//     // Routes for AdminListController
//     // Route::post('admins/{id}/verify-email', [\App\Http\Controllers\Backend\AdminListController::class, 'verifyEmail'])->name('admins.verify-email');
//     // Route::post('admins/{id}/send-reset-link', [\App\Http\Controllers\Backend\AdminListController::class, 'sendResetLink'])->name('admins.send-reset-link');

//     // // Routes for CustomerListController
//     // Route::post('customers/{id}/verify-email', [\App\Http\Controllers\Backend\CustomerListController::class, 'verifyEmail'])->name('customers.verify-email');
//     // Route::post('customers/{id}/send-reset-link', [\App\Http\Controllers\Backend\CustomerListController::class, 'sendResetLink'])->name('customers.send-reset-link');
   
// });

    
    
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

