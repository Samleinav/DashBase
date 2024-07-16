<?php

namespace Botble\Dashplugin\Http\Requests\Fronts\Auth;

use Botble\Base\Rules\EmailRule;
use Botble\Captcha\Facades\Captcha;
use Botble\Support\Http\Requests\Request;

class ProfileRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:120', 'min:2'],
            'last_name' => ['required', 'string', 'max:120', 'min:2'],
            'email' => ['required', 'max:60', 'min:6', new EmailRule(), 'unique:members'],
            'password' => ['required', 'min:6', 'confirmed'],
            'avatar_input' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        if (is_plugin_active('captcha')) {
            if (setting('member_enable_recaptcha_in_register_page', 0)) {
                $rules += Captcha::rules();
            }

            if (setting('member_enable_math_captcha_in_register_page', 0)) {
                $rules += Captcha::mathCaptchaRules();
            }
        }

        return $rules;
    }

    public function attributes(): array
    {
        return is_plugin_active('captcha') ? Captcha::attributes() : [];
    }
}
