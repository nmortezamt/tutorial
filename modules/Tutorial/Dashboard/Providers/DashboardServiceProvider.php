<?php

namespace Tutorial\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Tutorial\RolePermissions\Models\Permission;

class DashboardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/dashboard_route.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views','Dashboard');
        $this->mergeConfigFrom(__DIR__.'/../config/sidebar.php','sidebar');

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('Dashboard::layouts.header',function($view){
            $notifications = auth()->user()->unreadNotifications;
            $view->with(compact('notifications'));
        });

        $this->app->booted(function(){
            config()->set('sidebar.items.dashboard',[
                'icon'=> 'i-dashboard',
                'title' => 'پیشخوان',
                'url'=> route('dashboard.index'),
            ]);
        });
    }
}
