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
use Tutorial\User\Http\Controllers\UserController;

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
        ->name('password.reset.send')->middleware('throttle:5,30');


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

    Route::any('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::middleware(['web', 'auth'])->group(function(){
    Route::get('edit/profile',[UserController::class,'profile'])->name('users.profile');
    Route::post('edit/profile',[UserController::class,'updateProfile'])->name('users.update.profile');
    Route::post('users/update/photo',[UserController::class,'updatePhoto'])->name('users.photo');
    Route::get('users/{user}/info',[UserController::class,'info'])->name('users.info');

});

Route::middleware(['web', 'auth','verified'])->group(function(){
    Route::post('users/{user}/add/role',[UserController::class,'addRole'])->name('users.add_role');
    Route::delete('users/{user}/remove/{role}/role',[UserController::class,'removeRole'])->name('users.remove_role');
    Route::patch('users/{user}/manual-verify',[UserController::class,'manualVerify'])->name('users.manual.verify');
    Route::resource('users',UserController::class);
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['web', 'auth'])->name('dashboard');

// Route::middleware(['web', 'auth'])->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
