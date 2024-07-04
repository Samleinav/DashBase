<?php

namespace Botble\Dashplugin\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static CustomerStatusEnum ACTIVATED()
 * @method static CustomerStatusEnum LOCKED()
 */
class CustomerStatusEnum extends Enum
{
    public const ACTIVATED = 'activated';
    public const PENDING = 'pending';
    public const LOCKED = 'locked';
    public const DELETED = 'deleted';

    public static $langPath = 'plugins/dashplugin::customer.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::ACTIVATED => 'success',
            self::LOCKED => 'danger',
            self::PENDING => 'warning',
            self::DELETED => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
