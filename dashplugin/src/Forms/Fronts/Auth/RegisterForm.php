<?php

namespace Botble\Dashplugin\Forms\Fronts\Auth;

use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\PasswordField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Captcha\Facades\Captcha;
use Botble\Captcha\Forms\Fields\MathCaptchaField;
use Botble\Captcha\Forms\Fields\ReCaptchaField;
use Botble\Dashplugin\Forms\Fronts\Auth\FieldOptions\EmailFieldOption;
use Botble\Dashplugin\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\Dashplugin\Http\Requests\Fronts\Auth\RegisterRequest;
use Botble\Dashplugin\Models\Customer;

class RegisterForm extends AuthForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('customer.register.post'))
            ->setValidatorClass(RegisterRequest::class)
            ->icon('ti ti-user-plus')
            ->heading(__('Register an account'))
            ->description(__('Your personal data will be used to support your experience throughout this website, to manage access to your account.'))
            ->when(
                theme_option('register_background'),
                fn (AuthForm $form, string $background) => $form->banner($background)
            )
            ->add(
                'first_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('First name'))
                    ->placeholder(__('First name'))
                    ->icon('ti ti-user')
                    ->toArray()
            )
            ->add(
                'last_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Last name'))
                    ->placeholder(__('Last name'))
                    ->icon('ti ti-user')
                    ->toArray()
            )
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(__('Email'))
                    ->placeholder(__('Your email'))
                    ->icon('ti ti-mail')
                    ->toArray()
            )
            ->add(
                'password',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(__('Password'))
                    ->placeholder(__('Password'))
                    ->icon('ti ti-lock')
                    ->toArray()
            )
            ->add(
                'password_confirmation',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(__('Password confirmation'))
                    ->placeholder(__('Password confirmation'))
                    ->icon('ti ti-lock')
                    ->toArray()
            )
            ->when(is_plugin_active('captcha'), function (FormAbstract $form) {
                $form
                    ->when(Captcha::isEnabled() && setting('member_enable_recaptcha_in_register_page', false), function (FormAbstract $form) {
                        $form->add('recaptcha', ReCaptchaField::class);
                    })
                    ->when(Captcha::mathCaptchaEnabled() && setting('member_enable_math_captcha_in_register_page', false), function (FormAbstract $form) {
                        $form->add('math_captcha', MathCaptchaField::class);
                    });
            })
            ->submitButton(__('Register'), 'ti ti-arrow-narrow-right')
            ->add('login', HtmlField::class, [
                'html' => sprintf(
                    '<div class="mt-3 text-center">%s <a href="%s" class="text-decoration-underline">%s</a></div>',
                    __('Already have an account?'),
                    route('customer.login'),
                    __('Login')
                ),
            ])
            ->add('filters', HtmlField::class, [
                'html' => apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, Customer::class),
            ]);
    }
}
