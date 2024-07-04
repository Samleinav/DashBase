<?php

namespace Botble\Dashplugin\Http\Controllers\Settings;

use Botble\Base\Forms\FormBuilder;
use Botble\Dashplugin\Forms\Settings\DashpluginForm;
use Botble\Dashplugin\Http\Requests\Settings\DashpluginRequest;
use Botble\Setting\Http\Controllers\SettingController;

class DashpluginController extends SettingController
{
    public function edit(FormBuilder $formBuilder)
    {
        $this->pageTitle('Page title');

        return $formBuilder->create(DashpluginForm::class)->renderForm();
    }

    public function update(DashpluginRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
