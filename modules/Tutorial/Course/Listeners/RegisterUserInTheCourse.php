<?php

namespace Tutorial\Course\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Tutorial\Course\Models\Course;
use Tutorial\Course\Repositories\CourseRepo;

class RegisterUserInTheCourse
{

    public function __construct()
    {
        //
    }


    public function handle(object $event): void
    {
        if($event->payment->paymentable_type == Course::class){
            resolve(CourseRepo::class)->addStudentToCourse($event->payment->paymentable,$event->payment->buyer_id);
        }
    }
}
