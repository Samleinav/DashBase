<?php

namespace Botble\Dashplugin\Facades;

use Botble\Dashplugin\Supports\DashSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;


class DashHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DashSupport::class;
    }
}
