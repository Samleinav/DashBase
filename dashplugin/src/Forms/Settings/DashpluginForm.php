<?php

namespace Botble\Dashplugin\Forms\Settings;

use Botble\Dashplugin\Http\Requests\Settings\DashpluginRequest;
use Botble\Setting\Forms\SettingForm;

class DashpluginForm extends SettingForm
{
    public function buildForm(): void
    {
        parent::buildForm();

        $this
            ->setSectionTitle('Setting title')
            ->setSectionDescription('Setting description')
            ->setValidatorClass(DashpluginRequest::class);
    }
}
