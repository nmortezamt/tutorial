<?php

namespace Tutorial\Media\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::middleware('web')
        ->group(__DIR__.'./../Routes/media_route.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../Config/MediaFile.php','MediaFile');

    }

    public function boot()
    {

    }
}

