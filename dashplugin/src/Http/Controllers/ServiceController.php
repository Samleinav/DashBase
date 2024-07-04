<?php

namespace Botble\Dashplugin\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Dashplugin\Forms\ServiceForm;
use Botble\Dashplugin\Http\Requests\ServiceRequest;
use Botble\Dashplugin\Models\Service;
use Botble\Dashplugin\Tables\ServiceTable;

class ServiceController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/dashplugin::dash.name'))
            ->add(trans('plugins/dashplugin::service.name'), route('service.index'));
    }

    public function index(ServiceTable $table)
    {
        $this->pageTitle(trans('plugins/dashplugin::service.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/dashplugin::service.create'));

        return ServiceForm::create()->renderForm();
    }

    public function store(ServiceRequest $request)
    {
        $form = ServiceForm::create();
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('service.index'))
            ->setNextUrl(route('service.edit', $form->getModel()->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Service $service)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $service->name]));

        return ServiceForm::createFromModel($service)->renderForm();
    }

    public function update(Service $service, ServiceRequest $request)
    {
        ServiceForm::createFromModel($service)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('service.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Service $service)
    {
        return DeleteResourceAction::make($service);
    }
}
