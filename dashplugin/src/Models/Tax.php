<?php

namespace Botble\Dashplugin\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Tax extends BaseModel
{
    protected $table = 'dash_taxes';

    protected $fillable = [
        'title',
        'percentage',
        'priority',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'title' => SafeContent::class,
    ];
}
