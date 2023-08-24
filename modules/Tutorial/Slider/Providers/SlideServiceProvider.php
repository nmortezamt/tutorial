<?php

namespace Tutorial\Slider\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Tutorial\RolePermissions\Models\Permission;
use Tutorial\Slider\Models\Slide;
use Tutorial\Slider\Policies\SlidePolicy;

class SlideServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views/','Slider');
        Gate::policy(Slide::class,SlidePolicy::class);
    }

    public function boot()
    {
        Route::middleware(['web','auth'])
        ->group(__DIR__.'/../Routes/slide_route.php');

        $this->app->booted(function(){
            config()->set('sidebar.items.slider',[
                'icon'=> 'i-slideshow',
                'title' => 'اسلایدر',
                'url'=> route('slider.index'),
                'permission'=> [
                    Permission::PERMISSION_MANAGE_SLIDER,
                ],
            ]);
        });
    }
}

