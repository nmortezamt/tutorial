<?php

namespace Tutorial\RolePermissions\Providers;

use Illuminate\Support\ServiceProvider;

class RolePermissionServiceProvider extends ServiceProvider
{
    public function register() :void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/role-permission_route.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views','RolePermission');
        $this->loadJsonTranslationsFrom(__DIR__.'/../Lang');
    }

    public function boot() :void
    {
        config()->set('sidebar.items.rolePermissions',[
            'icon'=>'i-role-permissions',
            'title'=> 'نقشهای کاربری',
            'url'=>route('role-permissions.index'),
        ]);
    }
}
