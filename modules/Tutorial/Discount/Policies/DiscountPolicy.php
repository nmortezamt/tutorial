<?php

namespace Tutorial\Discount\Policies;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class DiscountPolicy
{
    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_DISCOUNT);
    }

}
