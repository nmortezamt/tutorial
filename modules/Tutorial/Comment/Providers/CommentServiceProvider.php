<?php

namespace Tutorial\Comment\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Tutorial\Comment\Models\Comment;
use Tutorial\Comment\Policies\CommentPolicy;
use Tutorial\RolePermissions\Models\Permission;

class CommentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views/','Comments');
        $this->loadJsonTranslationsFrom(__DIR__.'/../Lang/');
        Gate::policy(Comment::class,CommentPolicy::class);
    }

    public function boot()
    {
        $this->app->register(EventServiceProvider::class);
        Route::middleware(['web','auth'])
        ->group(__DIR__.'/../Routes/comment_route.php');

        $this->app->booted(function(){
            config()->set('sidebar.items.comments',[
                'icon'=> 'i-comments',
                'title' => 'نظرات',
                'url'=> route('comments.index'),
                'permission'=> [
                    Permission::PERMISSION_MANAGE_COMMENTS,
                    Permission::PERMISSION_MANAGE_TEACH,
                ],
            ]);
        });
    }
}

