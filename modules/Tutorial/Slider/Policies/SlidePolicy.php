<?php

namespace Tutorial\Slider\Policies;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class SlidePolicy
{

    public function __construct()
    {
        //
    }

    public function manage(User $user)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_SLIDER)) return true;
    }
}
