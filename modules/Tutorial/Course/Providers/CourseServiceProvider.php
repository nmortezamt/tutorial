<?php
namespace Tutorial\Course\Providers;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\ServiceProvider;
use Tutorial\Course\Database\Seeders\RolePermissionTableSeeder;

class CourseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/course_route.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views','Course');
        $this->loadJsonTranslationsFrom(__DIR__.'/../Lang/');
        $this->loadTranslationsFrom(__DIR__.'/../Lang/','Courses');
        DatabaseSeeder::$seeders[] = RolePermissionTableSeeder::class;
    }

    public function boot()
    {
        config()->set('sidebar.items.courses',[
            'icon'=>'i-courses',
            'title'=> 'دوره ها',
            'url'=>route('courses.index'),
        ]);
    }
}
