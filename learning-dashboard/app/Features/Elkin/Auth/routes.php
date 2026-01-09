<?php

use Illuminate\Support\Facades\Route;
use App\Features\Elkin\Auth\Http\Controllers\LoginController;
use App\Features\Elkin\Auth\Http\Controllers\RegisterController;
use App\Features\Elkin\Auth\Http\Controllers\LogoutController;

Route::middleware('guest:elkin')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::middleware('auth:elkin')->post('/logout', LogoutController::class)->name('logout');
