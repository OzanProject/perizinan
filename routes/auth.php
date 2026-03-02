<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('register', [RegisterController::class, 'register']);

    Route::get('login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('login', [LoginController::class, 'login']);

    // Forgot Password OTP Flow
    Route::get('forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendOtp'])->name('password.email');
    Route::get('verify-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showVerifyForm'])->name('password.otp.verify');
    Route::post('verify-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.submit');
    Route::get('reset-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('reset-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])->name('password.update.otp');
});

Route::middleware('auth')->group(function () {
    Route::put('password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [LoginController::class, 'logout'])
        ->name('logout');
});
