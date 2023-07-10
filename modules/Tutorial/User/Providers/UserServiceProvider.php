<?php

namespace Tutorial\User\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use Tutorial\User\Models\User;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/user_routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views/front','User');
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Tutorial\User\Database\factories\\'.class_basename($modelName).'Factory';
        });
        config()->set('auth.providers.users.model',User::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
