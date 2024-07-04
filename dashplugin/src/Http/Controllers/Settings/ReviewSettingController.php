<?php

namespace Botble\Dashplugin\Http\Controllers\Settings;

use Botble\Dashplugin\Forms\Settings\ReviewSettingForm;
use Botble\Dashplugin\Http\Requests\Settings\ReviewSettingRequest;
use Botble\Setting\Http\Controllers\SettingController;

class ReviewSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/dashplugin::settings.review.title'));

        return ReviewSettingForm::create()->renderForm();
    }

    public function update(ReviewSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
