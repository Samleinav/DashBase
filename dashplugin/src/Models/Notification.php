<?php

namespace Botble\Dashplugin\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Dashplugin\Enums\MessageStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'is_global',
    ];

    protected $casts = [
        'content' => SafeContent::class,
        'status' => MessageStatusEnum::class,
        
    ];

    public function to() 
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withDefault();
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'dash_global_notifications', 'notification_id', 'customer_id')
        ->withPivot('is_read')    
        ->withTimestamps();     
    }

    


    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id')->withDefault();
    }

}
