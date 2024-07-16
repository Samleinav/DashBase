<?php

namespace Botble\Dashplugin\Forms;

use Botble\Dashplugin\Enums\CustomerStatusEnum;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Dashplugin\Http\Requests\CustomerCreateRequest;
use Botble\Dashplugin\Models\Customer;
use Botble\Dashplugin\Abstract\Front\Form\FormFrontAbstract;
use Botble\Dashplugin\Models\CustomerRole;

class CustomerForm extends FormFrontAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Customer())
            ->setValidatorClass(CustomerCreateRequest::class)
            ->includeFiles()
            ->withCustomFields()
            ->add('first_name', 'text', [
                'label' => trans('plugins/dashplugin::customer.form.first_name'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/dashplugin::customer.form.first_name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('last_name', 'text', [
                'label' => trans('plugins/dashplugin::customer.form.last_name'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/dashplugin::customer.form.last_name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('email', 'text', [
                'label' => trans('plugins/dashplugin::customer.form.email'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/dashplugin::customer.form.email_placeholder'),
                    'data-counter' => 60,
                ],
            ])
            ->add('phone', 'text', [
                'label' => trans('plugins/dashplugin::customer.form.phone'),
                'attr' => [
                    'placeholder' => trans('plugins/dashplugin::customer.form.phone'),
                    'data-counter' => 20,
                ],
            ])
            ->add('is_change_password', 'onOff', [
                'label' => trans('plugins/dashplugin::customer.change_password'),
                'value' => 0,
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '#change-password',
                ],
                'wrapper' => [
                    'class' => $this->getModel()->id ? $this->formHelper->getConfig('defaults.wrapper_class') : 'd-none',
                ],
            ])
            ->add('openRow', 'html', [
                'html' => '<div id="change-password" class="row"' . ($this->getModel()->id ? ' style="display: none"' : null) . '>',
            ])
            ->add('password', 'password', [
                'label' => trans('plugins/dashplugin::customer.password'),
                'required' => true,
                'attr' => [
                    'data-counter' => 60,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('password_confirmation', 'password', [
                'label' => trans('plugins/dashplugin::customer.password_confirmation'),
                'required' => true,
                'attr' => [
                    'data-counter' => 60,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('closeRow', 'html', [
                'html' => '</div>',
            ])
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(CustomerStatusEnum::labels())->toArray())
            ->add('roles', 'select', [
                'choices' => CustomerRole::pluck('name', 'id')->toArray(),
            ])
            ->add('avatar', MediaImageField::class)
            ->setBreakFieldPoint('status');
    }
}
