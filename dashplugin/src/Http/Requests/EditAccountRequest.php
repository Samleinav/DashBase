<?php

namespace Botble\Dashplugin\Http\Requests;

use Botble\Base\Facades\BaseHelper;
use Botble\Support\Http\Requests\Request;

class EditAccountRequest extends Request
{
    public function rules(): array
    {   
        $rules = [
            'first_name' => ['required', 'max:60', 'min:2'],
            'last_name' => ['required', 'max:60', 'min:2'],
            'email' => 'required|max:60|min:6|email',
            'phone' => ['nullable', 'string', ...explode('|', BaseHelper::getPhoneValidationRule())],
            'avatar_input' => 'nullable|image|max:2048',
        ];

        if ($this->boolean('is_change_password')) {
            $rules['password'] = 'required|string|min:6';
            $rules['password_confirmation'] = 'required|same:password';
        }

        return $rules;
    }
}
