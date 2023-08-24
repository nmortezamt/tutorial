<?php

namespace Tutorial\Payment\Policies;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class SettlementPolicy
{
    public function index(User $user)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_SETTLEMENTS)||
        $user->hasPermissionTo(Permission::PERMISSION_MANAGE_TEACH)) 
        return true;
    }

    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_SETTLEMENTS);
    }
    
    public function create(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_TEACH);
    }
}
