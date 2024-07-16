<?php

namespace Botble\Dashplugin\Http\Requests\Fronts;

use Botble\Support\Http\Requests\Request;
use Botble\Dashplugin\Enums\MessageStatusEnum;
use Illuminate\Validation\Rule;
use Botble\Base\Rules\OnOffRule;

class NotificationRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120', 'min:2'],
            'content' => ['required', 'string', 'max:2000'],
            'url' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'string'],
            'status' => Rule::in(MessageStatusEnum::values()),
            'is_global' => new OnOffRule(),
            'customer_id' => ['nullable', 'exists:dash_customers,id'],
            'roles' => ['nullable', 'array'],
        ];
    }
}