<?php

namespace Botble\Dashplugin\Providers;

use Botble\Dashplugin\Events\RoleAssignmentEvent;
use Botble\Dashplugin\Events\RoleUpdateEvent;
use Botble\Dashplugin\Listeners\AddSitemapListener;
use Botble\Dashplugin\Listeners\RoleAssignmentListener;
use Botble\Dashplugin\Listeners\RoleUpdateListener;
use Botble\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RenderingSiteMapEvent::class => [
            AddSitemapListener::class,
        ],

        RoleAssignmentEvent::class => [
            RoleAssignmentListener::class,
        ],

        RoleUpdateEvent::class => [
            RoleUpdateListener::class,
        ],
     ];
}