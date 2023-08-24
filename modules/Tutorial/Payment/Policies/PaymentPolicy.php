<?php

namespace Tutorial\Payment\Policies;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class PaymentPolicy
{
    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_PAYMENT);
    }
}
