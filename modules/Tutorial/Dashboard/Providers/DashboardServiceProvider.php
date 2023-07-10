<?php

namespace Tutorial\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/dashboard_route.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views','Dashboard');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->booted(function(){
            config()->set('sidebar.items.dashboard',[
                'icon'=> 'i-dashboard',
                'title' => 'پیشخوان',
                'url'=> route('dashboard.index'),
            ]);
        });
    }
}
