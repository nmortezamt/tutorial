<?php

namespace Tutorial\Course\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Repositories\UserRepo;

class ValidTeacher implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = resolve(UserRepo::class)->findById($value);

         if(! $user->hasPermissionTo(Permission::PERMISSION_MANAGE_TEACH))
        $fail('کاربر انتخاب شده یک مدرس معتبر نیست');
    }
}
