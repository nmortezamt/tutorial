<?php

namespace Tutorial\RolePermissions\Models;

use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    const PERMISSION_MANAGE_CATEGORIES = 'manage_categories';
    const PERMISSION_MANAGE_USERS = 'manage_users';
    const PERMISSION_MANAGE_ROLE_PERMISSION = 'manage_role_permission';
    const PERMISSION_MANAGE_TEACH = 'teach';
    const PERMISSION_MANAGE_COURSE = 'manage_course';
    const PERMISSION_MANAGE_PAYMENT = 'manage_payment';
    const PERMISSION_MANAGE_SETTLEMENTS = 'manage_settlements';
    const PERMISSION_MANAGE_DISCOUNT = 'manage_discount';
    const PERMISSION_MANAGE_TICKETS = 'manage_tickets';
    const PERMISSION_MANAGE_COMMENTS = 'manage_comments';
    const PERMISSION_MANAGE_SLIDER = 'manage_slider';
    const PERMISSION_MANAGE_OWN_COURSE = 'manage_own_course';
    const PERMISSION_SUPER_ADMIN = 'super_admin';
    const PERMISSION_STUDENT = 'student';

    public static $permissions =
    [
        self::PERMISSION_SUPER_ADMIN,
        self::PERMISSION_MANAGE_TEACH,
        self::PERMISSION_MANAGE_CATEGORIES,
        self::PERMISSION_MANAGE_ROLE_PERMISSION,
        self::PERMISSION_MANAGE_COURSE,
        self::PERMISSION_MANAGE_PAYMENT,
        self::PERMISSION_MANAGE_SETTLEMENTS,
        self::PERMISSION_MANAGE_DISCOUNT,
        self::PERMISSION_MANAGE_TICKETS,
        self::PERMISSION_MANAGE_COMMENTS,
        self::PERMISSION_MANAGE_SLIDER,
        self::PERMISSION_MANAGE_OWN_COURSE,
        self::PERMISSION_MANAGE_USERS,
        self::PERMISSION_STUDENT,
    ];
}
