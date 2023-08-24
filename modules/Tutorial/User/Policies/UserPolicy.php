<?php

namespace Tutorial\User\Policies;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class UserPolicy
{

    public function index(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_USERS);
    }

    public function addRole(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_USERS);
    }

    public function removeRole(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_USERS);
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_USERS);
    }

    public function manualVerify(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_USERS);
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_USERS);
    }

    public function editProfile(User $user)
    {
        if(auth()->check()) return true;
    }
}
