<?php

namespace Tutorial\RolePermissions\Policies;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class RolePermissionPolicy
{
    public function index(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_ROLE_PERMISSION);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_ROLE_PERMISSION);
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_ROLE_PERMISSION);
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_ROLE_PERMISSION);
    }
}
