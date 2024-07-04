<?php

namespace Botble\Dashplugin\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static CustomerStatusEnum ACTIVATED()
 * @method static CustomerStatusEnum LOCKED()
 */
class MessageStatusEnum  extends Enum
{
    public const READ = 'read';
    public const UNREAD = 'unread';

    public static $langPath = 'plugins/dashplugin::customer.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::READ => 'success',
            self::UNREAD => 'warning',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
