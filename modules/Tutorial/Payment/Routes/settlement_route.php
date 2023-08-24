<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Payment\Http\Controllers\SettlementController;

Route::controller(SettlementController::class)->group(function(){
    Route::get('settlements','index')->name('settlements.index');
    Route::get('settlements/create','create')->name('settlements.create');
    Route::post('settlements/store','store')->name('settlements.store');
    Route::get('settlements/{settlement}/edit','edit')->name('settlements.edit');
    Route::patch('settlements/{settlement}/update','update')->name('settlements.update');

});
