<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

Route::get('/', function () {
    return view('index');
});

Route::get('/test',function(){
    Permission::create(['name'=>'manage_role-permission']);
    auth()->user()->givePermissionTo('manage_role-permission');
    return 'successfully';
});
