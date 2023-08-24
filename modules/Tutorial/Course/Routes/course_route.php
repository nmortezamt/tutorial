<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Course\Http\Controllers\CourseController;

Route::group(['middleware'=>['web','auth','verified']],function(){
    Route::resource('courses',CourseController::class);
    Route::patch('courses/{course}/accept',[CourseController::class,'accept'])->name('courses.accept');
    Route::patch('courses/{course}/reject',[CourseController::class,'reject'])->name('courses.reject');
    Route::patch('courses/{course}/lock',[CourseController::class,'lock'])->name('courses.lock');

    Route::get('courses/{course}/details',[CourseController::class,'details'])->name('courses.details');

    Route::post('courses/{course}/buy',[CourseController::class,'buy'])->name('courses.buy');

    Route::get('courses/{course}/download-links',[CourseController::class,'downloadLinks'])->name('courses.downloadLinks');

});
