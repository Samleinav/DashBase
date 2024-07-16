<?php

namespace Botble\Dashplugin\Events;

use Botble\Dashplugin\Models\CustomerRole;
use Botble\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RoleUpdateEvent extends Event
{
    use SerializesModels;

    public function __construct(public CustomerRole $role)
    {
    }
}
