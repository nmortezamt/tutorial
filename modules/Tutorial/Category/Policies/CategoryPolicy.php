<?php

namespace Tutorial\Category\Policies;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class CategoryPolicy
{

    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_CATEGORIES);
    }
}
