<?php

namespace Botble\Dashplugin\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Dashplugin\Enums\MessageStatusEnum;
use Botble\Base\Models\BaseModel;

class Notification extends BaseModel
{
    protected $table = 'dash_notifications';

    protected $fillable = [
        'title',
        'content',
        'status',
        'url',
        'image',
        'type',
        'customer_id',
        'service_id',
    ];

    protected $casts = [
        'content' => SafeContent::class,
        'status' => MessageStatusEnum::class,
        
    ];

    public function customer() 
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withDefault();
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id')->withDefault();
    }

}
