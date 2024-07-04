<?php

namespace Theme\Dashuser\Http\Controllers;

use Botble\Theme\Http\Controllers\PublicController;

class DashuserController extends PublicController
{
    public function getIndex()
    {
        
        return parent::getIndex();
    }

    public function getView(?string $key = null, string $prefix = '')
    {
        return parent::getView($key);
    }

    public function getSiteMapIndex(string $key = null, string $extension = 'xml')
    {
        return parent::getSiteMapIndex();
    }
}
