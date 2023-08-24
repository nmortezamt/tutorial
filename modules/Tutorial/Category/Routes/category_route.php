<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>['web','auth','verified'],'namespace'=>'Tutorial\Category\Http\Controllers'],function(){
    Route::resource('categories',CategoryController::class);
});

