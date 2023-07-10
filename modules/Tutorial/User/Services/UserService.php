<?php

namespace Tutorial\User\Services;

use Tutorial\User\Models\User;

class UserService
{
    public static function changePassword(User $user,$newPassword)
    {
        $user->password = bcrypt($newPassword);
        $user->save();
    }
}
