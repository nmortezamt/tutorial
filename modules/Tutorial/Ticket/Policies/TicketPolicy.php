<?php

namespace Tutorial\Ticket\Policies;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class TicketPolicy
{
    public function show(User $user, $ticket)
    {
        if(($user->id == $ticket->user_id) || ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_TICKETS))) return true;
    }

    public function delete(User $user)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_TICKETS)) return true;
    }
}
