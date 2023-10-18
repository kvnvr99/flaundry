<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\LoginController AS Login;
// use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Member\HomeController;

// Route::get('/', [Login::class, 'index']);
// Route::post('login-qr', [LoginController::class, 'loginQR'])->name('login-qr');

// Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('member');
});

