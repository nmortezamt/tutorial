<?php

namespace Tutorial\Ticket\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Tutorial\Ticket\Models\Reply;
use Tutorial\Ticket\Models\Ticket;
use Tutorial\Ticket\Policies\ReplyPolicy;
use Tutorial\Ticket\Policies\TicketPolicy;

class TicketServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views/','Ticket');
        $this->loadJsonTranslationsFrom(__DIR__.'/../Lang/');
        Gate::policy(Ticket::class,TicketPolicy::class);
        Gate::policy(Reply::class,ReplyPolicy::class);
    }

    public function boot()
    {
        Route::middleware(['web','auth'])->group(__DIR__.'/../Routes/route_ticket.php');
        config()->set('sidebar.items.tickets',[
            'icon'=>'i-tickets',
            'title'=> 'تیکت ها',
            'url'=>route('tickets.index'),
        ]);
    }
}
