<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'render'])->name('dashboard.index');

    Route::get('notifications/mark-all-as-read', [DashboardController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});
