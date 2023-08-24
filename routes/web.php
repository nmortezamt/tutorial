<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Media\Services\MediaFileService;
use Tutorial\Payment\Events\PaymentWasSuccessful;
use Tutorial\Payment\GateWays\GateWay;
use Tutorial\Payment\Models\Payment;
use Tutorial\RolePermissions\Models\Permission as ModelsPermission;

// Route::get('/', function () {
//     return view('index');
// });

Route::get('/test',function(){

    event(new PaymentWasSuccessful(new Payment()));

    // $gateway = resolve(GateWay::class);
    // $payment = new Payment();
    // $gateway->request($payment);
    // dd(MediaFileService::getExtensions());
    // Permission::create(['name'=>'manage_role-permission']);
//     auth()->user()->givePermissionTo([
//      ModelsPermission::PERMISSION_MANAGE_TEACH,
// ]);
// auth()->user()->assignRole(['teacher','']);

    // return auth()->user()->permissions;
});
