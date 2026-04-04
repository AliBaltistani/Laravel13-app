<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Admin auth routes (login/logout) — no is_admin middleware
            Route::middleware(['web'])
                ->prefix('admin')
                ->name('admin.')
                ->group(function () {
                    Route::middleware('guest')->group(function () {
                        Route::get('login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
                        Route::post('login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
                    });
                    Route::post('logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout')->middleware('auth');
                });

            // Admin panel routes — protected by auth + is_admin
            Route::middleware(['web', 'auth', 'is_admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
