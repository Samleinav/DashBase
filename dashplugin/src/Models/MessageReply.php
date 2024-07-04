<?php

namespace Botble\Dashplugin\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Dashplugin\Enums\MessageStatusEnum;
use Botble\Base\Models\BaseModel;

class MessageReply extends BaseModel
{
    protected $table = 'dash_messages_replies';

    protected $fillable = [
        'title',
        'content',
        'status',
        'link',
        'image',
        'type',
        'customer_id',
    ];

    protected $casts = [
        'content' => SafeContent::class,
        'status' => MessageStatusEnum::class,
    ];


}
