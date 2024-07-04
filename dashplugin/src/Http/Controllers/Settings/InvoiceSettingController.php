<?php

namespace Botble\Dashplugin\Http\Controllers\Settings;

use Botble\Dashplugin\Forms\Settings\InvoiceSettingForm;
use Botble\Dashplugin\Http\Requests\Settings\InvoiceSettingRequest;
use Botble\Setting\Http\Controllers\SettingController;

class InvoiceSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/dashplugin::settings.invoice.title'));

        return InvoiceSettingForm::create()->renderForm();
    }

    public function update(InvoiceSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
