<?php

namespace Tutorial\Course\Policies;

use Tutorial\Course\Models\Lesson;
use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class LessonPolicy
{
    public function edit(User $user ,$lesson)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE) || ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE) && $lesson->course->teacher_id == $user->id))
        return true;
    }

    public function delete(User $user ,$lesson)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE) || ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE) && $lesson->course->teacher_id == $user->id))
        return true;
    }

    public function download(User $user,$lesson)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSE) || $user->id == $lesson->course->teacher_id || $lesson->course->hasStudent($user->id) || $lesson->is_free) return true;
        return false;
    }
}
