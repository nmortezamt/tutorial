<?php

namespace Tutorial\Discount\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Tutorial\Discount\Models\Discount;
use Tutorial\Discount\Policies\DiscountPolicy;
use Tutorial\RolePermissions\Models\Permission;

class DiscountServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views','Discount');
        $this->loadJsonTranslationsFrom(__DIR__.'/../Lang/');
        Gate::policy(Discount::class,DiscountPolicy::class);
    }

    public function boot()
    {
        $this->app->register(EventServiceProvider::class);
        Route::middleware(['web','auth'])->group(__DIR__.'/../Routes/discount_route.php');
        $this->app->booted(function(){
            config()->set('sidebar.items.discount',[
                'icon'=> 'i-discounts',
                'title' => 'تخفیف ها',
                'url'=> route('discounts.index'),
                'permission'=> [
                    Permission::PERMISSION_MANAGE_DISCOUNT,
                ],
            ]);
        });
    }
}
