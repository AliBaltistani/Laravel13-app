<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');
