<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Discount\Http\Controllers\DiscountController;

Route::controller(DiscountController::class)->group(function(){
    Route::get('discounts','index')->name('discounts.index');
    Route::post('discounts/store','store')->name('discounts.store');
    Route::delete('discounts/{discount}/destroy','destroy')->name('discounts.destroy');
    Route::get('discounts/{discount}/edit','edit')->name('discounts.edit');
    Route::patch('discounts/{discount}/update','update')->name('discounts.update');
    Route::get('discounts/{code}/{course}/check','check')->name('discounts.check')->middleware('throttle:6,1');
});
