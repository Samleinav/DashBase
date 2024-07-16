<?php

namespace Botble\Dashplugin\Events;

use Botble\Dashplugin\Models\CustomerRole;
use Botble\Dashplugin\Models\Customer;
use Botble\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RoleAssignmentEvent extends Event
{
    use SerializesModels;

    public function __construct(public CustomerRole $role, public Customer $user)
    {
    }
}
