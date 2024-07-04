<?php

namespace Botble\Dashplugin\Forms;

use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\Dashplugin\Http\Requests\TaxRequest;
use Botble\Dashplugin\Models\Tax;

class TaxForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Tax())
            ->setValidatorClass(TaxRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => trans('plugins/dashplugin::tax.title'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/dashplugin::tax.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('percentage', 'number', [
                'label' => trans('plugins/dashplugin::tax.percentage'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/dashplugin::tax.percentage'),
                    'data-counter' => 120,
                ],
            ])
            ->add('priority', 'number', [
                'label' => trans('plugins/dashplugin::tax.priority'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/dashplugin::tax.priority'),
                    'data-counter' => 120,
                ],
            ])
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status');
    }
}
