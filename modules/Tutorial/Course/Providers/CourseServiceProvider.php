<?php
namespace Tutorial\Course\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Tutorial\Course\Models\Course;
use Tutorial\Course\Models\Lesson;
use Tutorial\Course\Models\Season;
use Tutorial\Course\Policies\CoursePolicy;
use Tutorial\Course\Policies\LessonPolicy;
use Tutorial\Course\Policies\SeasonPolicy;
use Tutorial\RolePermissions\Models\Permission;

class CourseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/course_route.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/season_route.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/lesson_route.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views','Course');
        $this->loadJsonTranslationsFrom(__DIR__.'/../Lang/');
        $this->loadTranslationsFrom(__DIR__.'/../Lang/','Courses');
        Gate::policy(Course::class,CoursePolicy::class);
        Gate::policy(Season::class,SeasonPolicy::class);
        Gate::policy(Lesson::class,LessonPolicy::class);

    }

    public function boot()
    {
        config()->set('sidebar.items.courses',[
            'icon'=>'i-courses',
            'title'=> 'دوره ها',
            'url'=>route('courses.index'),
            'permission'=> [
                Permission::PERMISSION_MANAGE_COURSE,
                Permission::PERMISSION_MANAGE_OWN_COURSE
            ],

        ]);
    }
}
