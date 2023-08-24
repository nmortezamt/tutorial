<?php

namespace Tutorial\User\Providers;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Tutorial\User\Database\seeders\UserTableSeeder;
use Tutorial\User\Http\Middleware\StoreUserIp;
use Tutorial\User\Models\User;
use Tutorial\User\Policies\UserPolicy;
use Tutorial\RolePermissions\Models\Permission;

class UserServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/user_routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views/front','User');
        $this->loadJsonTranslationsFrom(__DIR__.'/../lang/');
        DatabaseSeeder::$seeders[] = UserTableSeeder::class;
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Tutorial\User\Database\factories\\'.class_basename($modelName).'Factory';
        });
        config()->set('auth.providers.users.model',User::class);
        Gate::policy(User::class,UserPolicy::class);
    }


    public function boot(): void
    {
        $this->app['router']->pushMiddlewareToGroup('web', StoreUserIp::class);
        config()->set('sidebar.items.users',[
            'icon'=>'i-users',
            'title'=> 'کاربران',
            'url'=>route('users.index'),
            'permission'=> Permission::PERMISSION_MANAGE_USERS,
        ]);

        $this->app->booted(function(){
            config()->set('sidebar.items.users_information',[
                'icon'=>'i-user__inforamtion',
                'title'=> 'اطلاعات کاربری',
                'url'=>route('users.profile'),
            ]);
        });

    }
}
