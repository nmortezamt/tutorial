<?php

use Illuminate\Support\Facades\Route;
use Tutorial\User\Http\Controllers\Auth\AuthenticatedSessionController;
use Tutorial\User\Http\Controllers\Auth\EmailVerificationNotificationController;
use Tutorial\User\Http\Controllers\Auth\EmailVerificationPromptController;
use Tutorial\User\Http\Controllers\Auth\NewPasswordController;
use Tutorial\User\Http\Controllers\Auth\PasswordResetLinkController;
use Tutorial\User\Http\Controllers\Auth\RegisteredUserController;
use Tutorial\User\Http\Controllers\Auth\VerifyEmailController;
use Tutorial\User\Http\Controllers\ProfileController;

Route::middleware(['web', 'guest'])->group(function () {

    Route::get('register', [RegisteredUserController::class, 'render'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'render'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'render'])
        ->name('password.request');

    Route::get('password-reset-send', [PasswordResetLinkController::class, 'store'])
        ->name('password.reset.send');

    Route::post('password/resend/{user}', [EmailVerificationNotificationController::class, 'passwordSend'])
    ->middleware('throttle:6,1')
    ->name('password.resend');

    Route::post('password-reset-check-verify-code', [PasswordResetLinkController::class, 'checkVerifyCode'])
        ->name('password.check.verify.code')
        ->middleware('throttle:5,1');
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('password-change', [NewPasswordController::class, 'render'])
        ->name('password.show.reset.form');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store')->middleware('auth');

    Route::post('verify-email', [VerifyEmailController::class, 'verify'])->name('verification.verify')->middleware('throttle:5,1');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'verifySend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['web', 'auth'])->name('dashboard');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
