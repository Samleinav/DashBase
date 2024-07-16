<?php

namespace Botble\Dashplugin\Http\Controllers\Front\Settings;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Dashplugin\Facades\DashHelper;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Botble\Base\Supports\Breadcrumb;

class SettingsController extends BaseController
{

    protected function breadcrumb(): Breadcrumb
    {
        return DashHelper::breadcrumb()
        ->add(__('Home'), route('public.index'));
    }
    
    public function getIndex()
    {
        SeoHelper::setTitle(__('Settings'));
        
        $this->breadcrumb()
        ->add(__('Settings'), route('public.settings.index'));

        return Theme::scope('customer.settings.overview', [])
        ->render();
    }

}