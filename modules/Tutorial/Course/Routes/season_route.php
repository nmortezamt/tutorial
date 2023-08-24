<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Course\Http\Controllers\SeasonController;

Route::group(['middleware'=>['web','auth','verified']],function(){

    Route::post('seasons/{course}/store',[SeasonController::class,'store'])->name('seasons.store');

    Route::get('seasons/{season}/edit',[SeasonController::class,'edit'])->name('seasons.edit');

    Route::patch('seasons/{season}/update',[SeasonController::class,'update'])->name('seasons.update');

    Route::delete('seasons/{season}/destroy',[SeasonController::class,'destroy'])->name('seasons.destroy');

    Route::patch('seasons/{season}/accept',[SeasonController::class,'accept'])->name('seasons.accept');
    Route::patch('seasons/{season}/reject',[SeasonController::class,'reject'])->name('seasons.reject');
    Route::patch('seasons/{season}/lock',[SeasonController::class,'lock'])->name('seasons.lock');
    Route::patch('seasons/{season}/unlock',[SeasonController::class,'unlock'])->name('seasons.unlock');


});
