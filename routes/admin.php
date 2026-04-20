<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\HomepageSectionController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| All admin routes are prefixed with /admin and named admin.*
| Protected by auth + is_admin middleware (registered in bootstrap/app.php)
|
*/

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Admin Profile & Password
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.remove-avatar');
Route::get('profile/password', [ProfileController::class, 'showPasswordForm'])->name('profile.password');
Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

// Products
Route::resource('products', ProductController::class);
Route::post('products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk');
Route::get('products-export/csv', [ProductController::class, 'export'])->name('products.export');

// Categories
Route::resource('categories', CategoryController::class);

// Brands
Route::resource('brands', BrandController::class);

// Orders
Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders-export/csv', [OrderController::class, 'export'])->name('orders.export');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
Route::put('orders/{order}/tracking', [OrderController::class, 'updateTracking'])->name('orders.tracking');
Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

// Customers
Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('customers/{user}', [CustomerController::class, 'show'])->name('customers.show');
Route::put('customers/{user}/toggle', [CustomerController::class, 'toggleStatus'])->name('customers.toggle');
Route::post('customers/{user}/email', [CustomerController::class, 'sendEmail'])->name('customers.email');

// Coupons
Route::resource('coupons', CouponController::class);
Route::put('coupons/{coupon}/toggle', [CouponController::class, 'toggleStatus'])->name('coupons.toggle');
Route::post('coupons/{coupon}/duplicate', [CouponController::class, 'duplicate'])->name('coupons.duplicate');
Route::post('coupons/bulk-action', [CouponController::class, 'bulkAction'])->name('coupons.bulk');

// Flash Sales
Route::resource('flash-sales', FlashSaleController::class);

// Blog Posts
Route::resource('posts', PostController::class);

// Blog Categories
Route::resource('post-categories', PostCategoryController::class);

// Comments
Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
Route::put('comments/{comment}/toggle', [CommentController::class, 'toggle'])->name('comments.toggle');
Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::post('comments/bulk-approve', [CommentController::class, 'bulkApprove'])->name('comments.bulk-approve');

// CMS Pages
Route::resource('pages', AdminPageController::class);
Route::delete('pages/{page}/images/{image}', [AdminPageController::class, 'deleteImage'])->name('pages.delete-image');
Route::delete('pages/{page}/sections/{section}', [AdminPageController::class, 'deleteSection'])->name('pages.delete-section');

// Homepage Builder
Route::prefix('homepage')->name('homepage.')->group(function () {
    Route::get('/', [HomepageSectionController::class, 'index'])->name('index');
    Route::get('{section}/edit', [HomepageSectionController::class, 'edit'])->name('edit');
    Route::put('{section}', [HomepageSectionController::class, 'update'])->name('update');
    Route::put('{section}/toggle', [HomepageSectionController::class, 'toggleActive'])->name('toggle');
    Route::post('reorder', [HomepageSectionController::class, 'reorder'])->name('reorder');
    Route::get('custom/create', [HomepageSectionController::class, 'createCustom'])->name('custom.create');
    Route::post('custom', [HomepageSectionController::class, 'storeCustom'])->name('custom.store');
    Route::delete('{section}', [HomepageSectionController::class, 'destroyCustom'])->name('custom.destroy');
});

// Banners
Route::resource('banners', BannerController::class);

// Sliders
Route::resource('sliders', SliderController::class);
Route::get('sliders/{slider}/slides', [SliderController::class, 'slides'])->name('sliders.slides');
Route::post('sliders/{slider}/slides', [SliderController::class, 'storeSlide'])->name('sliders.slides.store');
Route::put('sliders/{slider}/slides/{slide}', [SliderController::class, 'updateSlide'])->name('sliders.slides.update');
Route::delete('sliders/{slider}/slides/{slide}', [SliderController::class, 'destroySlide'])->name('sliders.slides.destroy');

// Shipping
Route::resource('shipping-zones', ShippingController::class);
Route::post('shipping-methods', [ShippingController::class, 'storeMethod'])->name('shipping-methods.store');
Route::put('shipping-methods/{shippingMethod}', [ShippingController::class, 'updateMethod'])->name('shipping-methods.update');
Route::delete('shipping-methods/{shippingMethod}', [ShippingController::class, 'destroyMethod'])->name('shipping-methods.destroy');

// Reviews
Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::put('reviews/{review}/toggle', [ReviewController::class, 'toggle'])->name('reviews.toggle');
Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
Route::post('reviews/bulk-approve', [ReviewController::class, 'bulkApprove'])->name('reviews.bulk-approve');

// Newsletter
Route::get('newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');
Route::get('newsletter/export', [NewsletterController::class, 'export'])->name('newsletter.export');
Route::delete('newsletter/{subscriber}', [NewsletterController::class, 'destroy'])->name('newsletter.destroy');
Route::get('newsletter/broadcast', [NewsletterController::class, 'broadcastForm'])->name('newsletter.broadcast');
Route::post('newsletter/broadcast', [NewsletterController::class, 'sendBroadcast'])->name('newsletter.send-broadcast');

// Settings
Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
Route::post('settings/test-email', [SettingController::class, 'testEmail'])->name('settings.test-email');

// Reports
Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
Route::get('reports/products', [ReportController::class, 'products'])->name('reports.products');
Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
Route::get('reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
