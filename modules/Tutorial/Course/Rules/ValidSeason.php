<?php

namespace Tutorial\Course\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Tutorial\Course\Repositories\SeasonRepo;
use Tutorial\User\Repositories\UserRepo;

class ValidSeason implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $season = resolve(SeasonRepo::class)->findByIdAndCourseId($value,request()->route('course'));

         if(! $season)
        $fail('سرفصل انتخاب شده معتبر نیست');
    }
}
