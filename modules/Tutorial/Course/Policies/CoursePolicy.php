<?php

namespace Tutorial\Course\Policies;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class CoursePolicy
{
    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE);
    }

    public function index(User $user)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE) ||
        $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE)) return true;
    }

    public function create(User $user)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE) ||
        $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE)) return true;
    }

    public function edit(User $user, $course)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE)) return true;

        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE) && $user->id == $course->teacher_id) return true;
    }

    public function delete(User $user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE))
            return true;
    }

    public function change_confirmation_status(User $user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE))
            return true;
    }

    public function details(User $user,$course)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE)) return true;

        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE) && $course->teacher_id == $user->id) return true;
    }

    public function createSeason(User $user,$course)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE)) return true;

        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE) && $course->teacher_id == $user->id) return true;
    }

    public function createLesson(User $user,$course)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE) || ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE) && $course->teacher_id == $user->id))
        return true;
    }

    public function download(User $user,$course)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE) || $user->id == $course->teacher_id || $course->hasStudent($user->id)) return true;
        return false;
    }
}
