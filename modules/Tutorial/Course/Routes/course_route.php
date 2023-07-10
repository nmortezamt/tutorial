<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Course\Http\Controllers\CourseController;

Route::group(['middleware'=>['web','auth','verified']],function(){
    Route::resource('courses',CourseController::class);
    Route::patch('courses/{course}/accept',[CourseController::class,'accept'])->name('courses.accept');
    Route::patch('courses/{course}/reject',[CourseController::class,'reject'])->name('courses.reject');
    Route::patch('courses/{course}/lock',[CourseController::class,'lock'])->name('courses.lock');

});
