<?php

namespace Botble\Dashplugin\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;

class Currency extends BaseModel
{
    protected $table = 'dash_currencies';

    protected $fillable = [
        'title',
        'symbol',
        'is_prefix_symbol',
        'order',
        'decimals',
        'is_default',
        'exchange_rate',
    ];

    protected $casts = [
        'title' => SafeContent::class,
    ];
}
