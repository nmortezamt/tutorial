<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>['web','auth','verified','permission:manage_role_permission'],'namespace'=>'Tutorial\RolePermissions\Http\Controllers'],function(){
    Route::resource('role-permissions',RolePermissionController::class);
});

