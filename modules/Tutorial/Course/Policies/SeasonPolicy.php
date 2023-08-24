<?php

namespace Tutorial\Course\Policies;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class SeasonPolicy
{

    public function edit(User $user, $season)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE)) return true;

        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE) && $user->id == $season->course->teacher_id;
    }

    public function delete(User $user,$season)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE)) return true;

        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE) && $user->id == $season->course->teacher_id;
    }

    public function change_confirmation_status(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE);
    }
}
