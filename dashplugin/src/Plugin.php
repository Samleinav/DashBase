<?php

namespace Botble\Dashplugin;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('Dashplugins');
        Schema::dropIfExists('Dashplugins_translations');
    }
}
