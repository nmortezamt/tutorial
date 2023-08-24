<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Front\Http\Controllers\FrontController;

Route::middleware('web')->group(function(){
    Route::get('/',[FrontController::class,'index']);
    Route::get('/c-{slug}',[FrontController::class,'singleCourse'])->name('singleCourse');
    Route::get('tutors/{username}',[FrontController::class,'singleTutor'])->name('singleTutor');
});
