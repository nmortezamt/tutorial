<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['web','auth','verified'])->get('dashboard',[DashboardController::class,'render'])->name('dashboard.index');
