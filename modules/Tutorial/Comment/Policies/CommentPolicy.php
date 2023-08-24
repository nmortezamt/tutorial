<?php

namespace Tutorial\Comment\Policies;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class CommentPolicy
{
    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COMMENTS);
    }

    public function show(User $user,$comment)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COMMENTS) || $user->id == $comment->commentable->teacher_id) return true;
    }

    public function index(User $user)
    {
        if($user->hasAnyPermission([Permission::PERMISSION_MANAGE_COMMENTS,Permission::PERMISSION_MANAGE_TEACH])) return true;
    }

}
