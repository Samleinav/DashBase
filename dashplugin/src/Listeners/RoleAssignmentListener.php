<?php

namespace Botble\Dashplugin\Listeners;

use Botble\Dashplugin\Events\RoleAssignmentEvent;

class RoleAssignmentListener
{
    public function handle(RoleAssignmentEvent $event): void
    {
        $permissions = $event->role->permissions;
        $permissions[ACL_ROLE_SUPER_USER] = $event->user->super_user;
        $permissions[ACL_ROLE_MANAGE_SUPERS] = $event->user->manage_supers;

        $event->user->permissions = $permissions;
        $event->user->roles()->sync([$event->role->id]);
        $event->user->save();

    }
}
