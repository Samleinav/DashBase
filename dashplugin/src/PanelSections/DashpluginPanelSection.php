<?php

namespace Botble\Dashplugin\PanelSections;

use Botble\Base\PanelSections\PanelSection;

class DashpluginPanelSection extends PanelSection
{
    public function setup(): void
    {
        $this
            ->setId('settings.{id}')
            ->setTitle('{title}')
            ->withItems([
                //
            ]);
    }
}
