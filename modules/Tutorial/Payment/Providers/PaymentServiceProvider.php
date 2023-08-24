<?php

namespace Tutorial\Payment\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Tutorial\Payment\GateWays\GateWay;
use Tutorial\Payment\GateWays\Zarinpal\ZarinpalAdaptor;
use Tutorial\Payment\Models\Payment;
use Tutorial\Payment\Models\Settlement;
use Tutorial\Payment\Policies\PaymentPolicy;
use Tutorial\Payment\Policies\SettlementPolicy;
use Tutorial\RolePermissions\Models\Permission;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views','Payment');
        $this->loadJsonTranslationsFrom(__DIR__.'/../Lang');
        Gate::policy(Payment::class,PaymentPolicy::class);
        Gate::policy(Settlement::class,SettlementPolicy::class);
    }

    public function boot()
    {
        $this->app->register(EventServiceProvider::class);
        Route::middleware(['web','auth'])->group(__DIR__.'/../Routes/payment_route.php');
        Route::middleware(['web','auth'])->group(__DIR__.'/../Routes/settlement_route.php');
        $this->app->singleton(GateWay::class,function($app){
            return new ZarinpalAdaptor();
        });
        $this->app->booted(function(){
            config()->set('sidebar.items.payments',[
                'icon'=>'i-transactions',
                'title'=> 'تراکنش ها',
                'url'=>route('payments.index'),
                'permission'=> [
                    Permission::PERMISSION_MANAGE_PAYMENT,
                ],

            ]);
            config()->set('sidebar.items.mypurchases',[
                'icon'=>'i-my__purchases',
                'title'=> 'خریدهای من',
                'url'=>route('purchases'),
            ]);

            config()->set('sidebar.items.settlements',[
                'icon'=>'i-checkouts',
                'title'=> 'تسویه حساب ها',
                'url'=>route('settlements.index'),
                'permission'=> [
                    Permission::PERMISSION_MANAGE_SETTLEMENTS,
                    Permission::PERMISSION_MANAGE_TEACH,
                ],

            ]);

            config()->set('sidebar.items.settlementRequest',[
                'icon'=>'i-checkout__request',
                'title'=> 'درخواست تسویه',
                'url'=>route('settlements.create'),
                'permission'=> [
                    Permission::PERMISSION_MANAGE_TEACH,
                ],

            ]);
        });


    }
}

