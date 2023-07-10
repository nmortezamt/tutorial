<?php

namespace Tutorial\Category\Providers;

use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/category_route.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views','Category');
        $this->mergeConfigFrom(__DIR__.'/../config/sidebar.php','sidebar');
    }
    public function boot()
    {
        config()->set('sidebar.items.categories',[
            'icon'=>'i-categories',
            'title'=> 'دسته بندی',
            'url'=>route('categories.index'),
        ]);
    }
}
