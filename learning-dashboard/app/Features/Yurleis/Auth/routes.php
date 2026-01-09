<?php

use Illuminate\Support\Facades\Route;
use App\Features\Yurleis\Auth\Http\Controllers\LoginController;
use App\Features\Yurleis\Auth\Http\Controllers\RegisterController;
use App\Features\Yurleis\Auth\Http\Controllers\LogoutController;

Route::middleware('guest:yurleis')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::middleware('auth:yurleis')->post('/logout', LogoutController::class)->name('logout');
