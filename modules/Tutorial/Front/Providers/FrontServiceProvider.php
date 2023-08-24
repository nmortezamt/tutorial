<?php

namespace Tutorial\Front\Providers;

use Illuminate\Support\ServiceProvider;
use Tutorial\Category\Repositories\CategoryRepo;
use Tutorial\Course\Repositories\CourseRepo;
use Tutorial\Slider\Repositories\SlideRepo;

class FrontServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views/','Front');
        $this->loadRoutesFrom(__DIR__.'/../Routes/front_route.php');
    }

    public function boot()
    {
        view()->composer('Front::layouts.header',function($view){
            $categories = (new CategoryRepo)->tree();
            $view->with(compact('categories'));
        });
        view()->composer('Front::layouts.latest-courses',function($view){
            $latestCourses = (new CourseRepo)->latestCourses();
            $view->with(compact('latestCourses'));
        });

        view()->composer('Front::layouts.slider',function($view){
            $slides = (new SlideRepo)->all();
            $view->with(compact('slides'));
        });
    }
}
