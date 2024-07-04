<?php

namespace Botble\Dashplugin\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Service extends BaseModel
{
    protected $table = 'dash_services';

    protected $fillable = [
        'name',
        'description',
        'content',
        'price',
        'price_type',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
    ];
}
