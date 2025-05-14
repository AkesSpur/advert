<?php

use App\Http\Controllers\Admin\CustomCategoryController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SeoTemplateController;
use App\Http\Controllers\Backend\AdminListController;
use App\Http\Controllers\Backend\ChatController;
use App\Http\Controllers\Backend\CustomerListController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\FilterManageController;
use App\Http\Controllers\Backend\FooterMenuController;
use App\Http\Controllers\Backend\FundManagementController;
use App\Http\Controllers\Backend\ManageUserController;
use App\Http\Controllers\Backend\NeighborhoodController;
use App\Http\Controllers\Backend\MetroStationController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\PaidServiceController;
use App\Http\Controllers\Backend\TopMenuController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;


/* Dashboard route */
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

/* profile management routes */
Route::prefix('profiles')->name('profiles.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/{id}', [ProfileController::class, 'show'])->name('show');
    Route::post('/{id}/disable', [ProfileController::class, 'disable'])->name('disable');
    Route::post('/{id}/restore', [ProfileController::class, 'restore'])->name('restore');
    Route::delete('/{id}', [ProfileController::class, 'destroy'])->name('destroy');
});

/* verification routes */
Route::get('verifications', [VerificationController::class, 'adminVerificationList'])->name('verifications.index');
Route::get('verifications/{id}', [VerificationController::class, 'adminViewVerification'])->name('verifications.show');
Route::post('verifications/{id}/process', [VerificationController::class, 'adminProcessVerification'])->name('verifications.process');

/* messenger routes */
Route::get('messenger', [ChatController::class, 'index'])->name('messenger.index');
Route::post('chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::get('chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
Route::post('chat/create-conversation', [ChatController::class, 'createConversation'])->name('chat.create-conversation');

/** customer list routes */
Route::put('customer/status-change', [CustomerListController::class, 'changeStatus'])->name('customer.status-change');
Route::get('customer', [CustomerListController::class, 'index'])->name('customer.index');
/** admin list routes */
Route::get('admin-list', [AdminListController::class, 'index'])->name('admin-list.index');
Route::put('admin-list/status-change', [AdminListController::class, 'changeStatus'])->name('admin-list.status-change');
Route::delete('admin-list/{id}', [AdminListController::class, 'destroy'])->name('admin-list.destroy');
/** Add and withdraw funds routes */
Route::get('add-fund/{id}', [FundManagementController::class, 'addIndex'])->name('add-fund.index');
Route::post('fund-user/{id}', [FundManagementController::class, 'addFund'])->name('fund-user');
Route::get('withdraw-fund/{id}', [FundManagementController::class, 'withdrawIndex'])->name('withdraw-fund.index');
Route::post('withdraw-user-fund/{id}', [FundManagementController::class, 'withdrawFund'])->name('withdraw-user-fund');

/** manage user routes */
Route::get('manage-user', [ManageUserController::class, 'index'])->name('manage-user.index');
Route::post('manage-user', [ManageUserController::class, 'create'])->name('manage-user.create');

/** Neighborhood routes */
Route::get('neighborhoods', [NeighborhoodController::class, 'index'])->name('neighborhoods.index');
Route::get('neighborhoods/create', [NeighborhoodController::class, 'create'])->name('neighborhoods.create');
Route::post('neighborhoods', [NeighborhoodController::class, 'store'])->name('neighborhoods.store');
Route::get('neighborhoods/{id}/edit', [NeighborhoodController::class, 'edit'])->name('neighborhoods.edit');
Route::put('neighborhoods/{id}', [NeighborhoodController::class, 'update'])->name('neighborhoods.update');
Route::delete('neighborhoods/{id}', [NeighborhoodController::class, 'destroy'])->name('neighborhoods.destroy');

/** Metro Station routes */
Route::get('metro-stations', [MetroStationController::class, 'index'])->name('metro-stations.index');
Route::get('metro-stations/create', [MetroStationController::class, 'create'])->name('metro-stations.create');
Route::post('metro-stations', [MetroStationController::class, 'store'])->name('metro-stations.store');
Route::get('metro-stations/{id}/edit', [MetroStationController::class, 'edit'])->name('metro-stations.edit');
Route::put('metro-stations/{id}', [MetroStationController::class, 'update'])->name('metro-stations.update');
Route::delete('metro-stations/{id}', [MetroStationController::class, 'destroy'])->name('metro-stations.destroy');

/** Service routes */
Route::get('services', [ServiceController::class, 'index'])->name('services.index');
Route::get('services/create', [ServiceController::class, 'create'])->name('services.create');
Route::post('services', [ServiceController::class, 'store'])->name('services.store');
Route::get('services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
Route::put('services/{id}', [ServiceController::class, 'update'])->name('services.update');
Route::delete('services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');

/** Paid Service routes */
Route::get('paid-services', [PaidServiceController::class, 'index'])->name('paid-services.index');
Route::get('paid-services/create', [PaidServiceController::class, 'create'])->name('paid-services.create');
Route::post('paid-services', [PaidServiceController::class, 'store'])->name('paid-services.store');
Route::get('paid-services/{id}/edit', [PaidServiceController::class, 'edit'])->name('paid-services.edit');
Route::put('paid-services/{id}', [PaidServiceController::class, 'update'])->name('paid-services.update');
Route::delete('paid-services/{id}', [PaidServiceController::class, 'destroy'])->name('paid-services.destroy');


/** comment routes */
Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
Route::delete('comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

/** review routes */
Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::delete('reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

 // Filter Management: Age
Route::get('filters/age', [FilterManageController::class, 'ageIndex'])->name('filters.age.index');
Route::get('filters/age/{id}/edit', [FilterManageController::class, 'ageEdit'])->name('filters.age.edit');
Route::put('filters/age/{id}', [FilterManageController::class, 'ageUpdate'])->name('filters.age.update');
// Route::put('filters/age/change-status', [FilterManageController::class, 'ageChangeStatus'])->name('filters.age.change-status');

// Filter Management: HairColor
Route::get('filters/hair-color', [FilterManageController::class, 'hairColorIndex'])->name('filters.hair-color.index');
Route::get('filters/hair-color/{id}/edit', [FilterManageController::class, 'hairColorEdit'])->name('filters.hair-color.edit');
Route::put('filters/hair-color/{id}', [FilterManageController::class, 'hairColorUpdate'])->name('filters.hair-color.update');

// Filter Management: Height
Route::get('filters/height', [FilterManageController::class, 'heightIndex'])->name('filters.height.index');
Route::get('filters/height/{id}/edit', [FilterManageController::class, 'heightEdit'])->name('filters.height.edit');
Route::put('filters/height/{id}', [FilterManageController::class, 'heightUpdate'])->name('filters.height.update');

// Filter Management: Weight
Route::get('filters/weight', [FilterManageController::class, 'weightIndex'])->name('filters.weight.index');
Route::get('filters/weight/{id}/edit', [FilterManageController::class, 'weightEdit'])->name('filters.weight.edit');
Route::put('filters/weight/{id}', [FilterManageController::class, 'weightUpdate'])->name('filters.weight.update');

// Filter Management: Size
Route::get('filters/size', [FilterManageController::class, 'sizeIndex'])->name('filters.size.index');
Route::get('filters/size/{id}/edit', [FilterManageController::class, 'sizeEdit'])->name('filters.size.edit');
Route::put('filters/size/{id}', [FilterManageController::class, 'sizeUpdate'])->name('filters.size.update');

// Filter Management: Price
Route::get('filters/price', [FilterManageController::class, 'priceIndex'])->name('filters.price.index');
Route::get('filters/price/{id}/edit', [FilterManageController::class, 'priceEdit'])->name('filters.price.edit');
Route::put('filters/price/{id}', [FilterManageController::class, 'priceUpdate'])->name('filters.price.update');

/** Custom Category routes */
Route::put('custom-category/status-change', [CustomCategoryController::class, 'changeStatus'])->name('custom-category.status-change');
Route::resource('custom-category', CustomCategoryController::class);

// menus
Route::get('top-menu', [TopMenuController::class, 'index'])->name('top-menu.index');
Route::get('top-menu/{id}/edit', [TopMenuController::class, 'edit'])->name('top-menu.edit');
Route::put('top-menu/{id}', [TopMenuController::class, 'update'])->name('top-menu.update');
Route::put('top-top-/status-change', [TopMenuController::class, 'changeStatus'])->name('top-menu.status-change');

Route::get('footer-menu', [FooterMenuController::class, 'index'])->name('footer-menu.index');
Route::get('footer-menu/{id}/edit', [FooterMenuController::class, 'edit'])->name('footer-menu.edit');
Route::put('footer-menu/{id}', [FooterMenuController::class, 'update'])->name('footer-menu.update');
Route::put('footer-footer-/status-change', [FooterMenuController::class, 'changeStatus'])->name('footer-menu.status-change');

// Admin SEO Templates Routes
    Route::get('seo-templates/{pageType?}/edit', [SeoTemplateController::class, 'edit'])->name('seo_templates.edit')->defaults('pageType', 'profile');
    Route::put('seo-templates/{pageType?}', [SeoTemplateController::class, 'update'])->name('seo_templates.update')->defaults('pageType', 'profile');
