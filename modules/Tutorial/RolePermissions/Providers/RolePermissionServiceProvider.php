<?php

namespace Tutorial\RolePermissions\Providers;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Tutorial\RolePermissions\Database\seeders\RolePermissionTableSeeder;
use Tutorial\RolePermissions\Models\Permission;
use Tutorial\RolePermissions\Models\Role;
use Tutorial\RolePermissions\Policies\RolePermissionPolicy;

class RolePermissionServiceProvider extends ServiceProvider
{
    public function register() :void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/role-permission_route.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views','RolePermission');
        $this->loadJsonTranslationsFrom(__DIR__.'/../Lang');
        DatabaseSeeder::$seeders[] = RolePermissionTableSeeder::class;
        Gate::policy(Role::class,RolePermissionPolicy::class);
        Gate::before(function($user){
            return $user->hasPermissionTo(Permission::PERMISSION_SUPER_ADMIN)
            ? true : null;
        });
    }

    public function boot() :void
    {
        config()->set('sidebar.items.rolePermissions',[
            'icon'=>'i-role-permissions',
            'title'=> 'نقشهای کاربری',
            'url'=>route('role-permissions.index'),
            'permission'=> Permission::PERMISSION_MANAGE_ROLE_PERMISSION,
        ]);
    }
}
