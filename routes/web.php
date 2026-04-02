<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Newsletter
Route::post('/newsletter/subscribe', function () {
    request()->validate(['email' => 'required|email']);
    \App\Models\NewsletterSubscriber::firstOrCreate(
        ['email' => request('email')],
        ['name' => null, 'token' => \Illuminate\Support\Str::random(64)]
    );
    return back()->with('success', 'Thank you for subscribing!');
})->name('newsletter.subscribe');

// Shop placeholder routes (Phase 3+)
Route::get('/shop', fn() => view('pages.placeholder', ['title' => 'Shop']))->name('shop');
Route::get('/shop/category/{slug}', fn($slug) => view('pages.placeholder', ['title' => "Category: $slug"]))->name('shop.category');
Route::get('/product/{slug}', fn($slug) => view('pages.placeholder', ['title' => "Product: $slug"]))->name('product.show');
Route::get('/cart', fn() => view('pages.placeholder', ['title' => 'Shopping Cart']))->name('cart');
Route::get('/checkout', fn() => view('pages.placeholder', ['title' => 'Checkout']))->name('checkout');
Route::get('/wishlist', fn() => view('pages.placeholder', ['title' => 'Wishlist']))->name('wishlist');
Route::get('/blog', fn() => view('pages.placeholder', ['title' => 'Blog']))->name('blog');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Account Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', fn() => view('pages.placeholder', ['title' => 'My Orders']))->name('orders');
    Route::get('/addresses', fn() => view('pages.placeholder', ['title' => 'My Addresses']))->name('addresses');
    Route::get('/details', fn() => view('pages.placeholder', ['title' => 'Account Details']))->name('details');
});
