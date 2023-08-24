<?php

use Illuminate\Support\Facades\Route;
use Tutorial\Media\Http\Controllers\MediaController;

Route::middleware('auth')->group(function(){
    Route::get('media/{media}/download',[MediaController::class,'download'])->name('download.media');
});
