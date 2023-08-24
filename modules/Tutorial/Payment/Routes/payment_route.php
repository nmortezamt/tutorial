<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Payment\Http\Controllers\PaymentController;

Route::controller(PaymentController::class)->group(function () {
    Route::get('/payments', 'index')->name('payments.index');
    Route::any('/payments/callback', 'callback')->name('payments.callback');
    Route::get('/purchases', 'purchases')->name('purchases');
});
