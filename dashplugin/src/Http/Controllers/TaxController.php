<?php

namespace Botble\Dashplugin\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Dashplugin\Forms\TaxForm;
use Botble\Dashplugin\Http\Requests\TaxRequest;
use Botble\Dashplugin\Models\Tax;
use Botble\Dashplugin\Tables\TaxTable;

class TaxController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/dashplugin::dashplugin.name'))
            ->add(trans('plugins/dashplugin::tax.name'), route('tax.index'));
    }

    public function index(TaxTable $dataTable)
    {
        $this->pageTitle(trans('plugins/dashplugin::tax.name'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/dashplugin::tax.create'));

        return TaxForm::create()->renderForm();
    }

    public function store(TaxRequest $request)
    {
        $form = TaxForm::create();
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('tax.index'))
            ->setNextUrl(route('tax.edit', $form->getModel()->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Tax $tax)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $tax->title]));

        return TaxForm::createFromModel($tax)->renderForm();
    }

    public function update(Tax $tax, TaxRequest $request)
    {
        TaxForm::createFromModel($tax)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('tax.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Tax $tax)
    {
        return DeleteResourceAction::make($tax);
    }
}
