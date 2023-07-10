<?php

namespace Tutorial\Media\Providers;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/../Routes/course_route.php');
        // $this->loadViewsFrom(__DIR__.'/../Resources/views','Course');
        // $this->loadJsonTranslationsFrom(__DIR__.'/../Lang/');
        // $this->loadTranslationsFrom(__DIR__.'/../Lang/','Courses');
    }

    public function boot()
    {

    }
}

