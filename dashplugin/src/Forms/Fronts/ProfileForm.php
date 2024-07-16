<?php

namespace Botble\Dashplugin\Forms\Fronts;

use Botble\Dashplugin\Enums\CustomerStatusEnum;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Dashplugin\Http\Requests\CustomerCreateRequest;
use Botble\Dashplugin\Models\Customer;
use Botble\Dashplugin\Abstract\Front\Form\FormFrontAbstract;

class ProfileForm extends FormFrontAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Customer())
            ->setValidatorClass(CustomerCreateRequest::class)
            ->includeFiles()
            ->onlySave()
            ->withCustomFields()

            // Abrir las pestañas
             // Abrir las pestañas
            ->tabs([
                'GeneralTab' => 'General',
                'AvatarTab' => 'Avatar'
            ])

            // Abrir el tab "General"
            ->openTab('GeneralTab')
            // Campos del tab "General"
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

            // Cerrar el tab "General" y abrir el tab "Avatar"
            ->closeTab()
            ->openTab('AvatarTab')
                // Campo 'avatar' en el tab "Avatar"
                ->add('avatar', MediaImageField::class, [
                    'label' => trans('plugins/dashplugin::customer.form.avatar'),
                ])
            ->closeTab()
            ->closeTabs();

        }
}
