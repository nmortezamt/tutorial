<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>['web','auth','verified'],'namespace'=>'Tutorial\RolePermissions\Http\Controllers'],function(){
    Route::resource('role-permissions',RolePermissionController::class);
});

