<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// SEO Routes (Phase 9-C, 9-D)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Password Reset — OTP-based flow
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
    Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp.form');
    Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
    Route::post('/forgot-password/resend', [ForgotPasswordController::class, 'resendOtp'])->name('password.otp.resend');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/brand/{slug}', [ShopController::class, 'brand'])->name('shop.brand');
Route::get('/shop/search', [ShopController::class, 'search'])->name('shop.search');

// Product
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product/quick-view/{product:slug}', [ShopController::class, 'quickView'])->name('product.quick-view');

// Cart & Checkout (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/cart', fn() => view('pages.cart'))->name('cart');
    Route::get('/checkout', fn() => view('pages.checkout'))->name('checkout');
    Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
});

// PayPal payment callbacks (Phase 5-F)
Route::get('/checkout/paypal/return/{orderNumber}', [CheckoutController::class, 'paypalReturn'])->name('checkout.paypal.return');
Route::get('/checkout/paypal/cancel/{orderNumber}', [CheckoutController::class, 'paypalCancel'])->name('checkout.paypal.cancel');

// Stripe Webhook (Phase 5-E) — no CSRF
Route::post('/webhook/stripe', [CheckoutController::class, 'stripeWebhook'])->name('webhook.stripe')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Wishlist (standalone page)
Route::get('/wishlist', function () {
    if (!auth()->check()) return redirect()->route('login');
    return app(AccountController::class)->wishlist();
})->name('wishlist');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/promotions', [PageController::class, 'promotions'])->name('promotions');

// Legal Pages
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// CMS Pages (catch-all for dynamic pages)
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Customer Account
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNumber}', [AccountController::class, 'orderDetail'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [AccountController::class, 'cancelOrder'])->name('orders.cancel');
    Route::get('/orders/{order}/invoice', [AccountController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}', [AccountController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AccountController::class, 'deleteAddress'])->name('addresses.destroy');
    Route::get('/addresses/{address}/default/{type}', [AccountController::class, 'setDefaultAddress'])->name('addresses.default');
    Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');
    Route::get('/details', [AccountController::class, 'profile'])->name('details');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [AccountController::class, 'changePassword'])->name('password.update');
});

// Wishlist remove (from account wishlist)
Route::delete('/wishlist/{wishlist}/remove', function (\App\Models\Wishlist $wishlist) {
    if ($wishlist->user_id !== auth()->id()) abort(403);
    $wishlist->delete();
    return back()->with('success', 'Removed from wishlist.');
})->middleware('auth');
