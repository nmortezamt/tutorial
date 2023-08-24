<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Slider\Http\Controllers\SlideController;

Route::resource('slider',SlideController::class);
