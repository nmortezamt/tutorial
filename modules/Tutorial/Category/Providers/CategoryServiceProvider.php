<?php

namespace Tutorial\Category\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Tutorial\Category\Models\Category;
use Tutorial\Category\Policies\CategoryPolicy;
use Tutorial\RolePermissions\Models\Permission;

class CategoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/category_route.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views','Category');
        Gate::policy(Category::class,CategoryPolicy::class);
    }
    public function boot()
    {
        config()->set('sidebar.items.categories',[
            'icon'=>'i-categories',
            'title'=> 'دسته بندی',
            'url'=>route('categories.index'),
            'permission'=> Permission::PERMISSION_MANAGE_CATEGORIES,
        ]);
    }
}
