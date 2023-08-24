<?php

namespace Tutorial\RolePermissions\Models;

use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    const ROLE_TEACHER = 'teacher';
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_STUDENT = 'student';

    public static $roles =
    [
        self::ROLE_TEACHER => [
            Permission::PERMISSION_MANAGE_TEACH,
        ],
        self::ROLE_SUPER_ADMIN => [
            Permission::PERMISSION_SUPER_ADMIN,
        ],
        self::ROLE_STUDENT => [
            Permission::PERMISSION_STUDENT,
        ],
    ];
}
