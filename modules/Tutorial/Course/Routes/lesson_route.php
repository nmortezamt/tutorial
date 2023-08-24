<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Course\Http\Controllers\LessonController;

Route::group(['middleware'=>['web','auth','verified']],function(){

    Route::get('courses/{course}/lessons/create',[LessonController::class,'create'])->name('lessons.create');

    Route::post('courses/{course}/lessons/store',[LessonController::class,'store'])->name('lessons.store');

    Route::get('courses/{course}/lessons/{lesson}/edit',[LessonController::class,'edit'])->name('lessons.edit');

    Route::patch('courses/{course}/lessons/{lesson}/update',[LessonController::class,'update'])->name('lessons.update');

    Route::delete('lessons/{lesson}/destroy',[LessonController::class,'destroy'])->name('lessons.destroy');

    Route::delete('lessons/destroy-multiple',[LessonController::class,'destroyMultiple'])->name('lessons.destroyMultiple');

    Route::patch('lessons/{lesson}/accept',[LessonController::class,'accept'])->name('lessons.accept');

    Route::patch('courses/{course}/lessons/accept-all',[LessonController::class,'acceptAll'])->name('lessons.acceptAll');

    Route::patch('courses/{course}/lessons/accept-multiple',[LessonController::class,'acceptMultiple'])->name('lessons.acceptMultiple');

    Route::patch('courses/{course}/lessons/reject-multiple',[LessonController::class,'rejectMultiple'])->name('lessons.rejectMultiple');


    Route::patch('lessons/{lesson}/reject',[LessonController::class,'reject'])->name('lessons.reject');

    Route::patch('lessons/{lesson}/lock',[LessonController::class,'lock'])->name('lessons.lock');

    Route::patch('lessons/{lesson}/unlock',[LessonController::class,'unlock'])->name('lessons.unlock');

});
