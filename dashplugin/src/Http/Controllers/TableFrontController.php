<?php

namespace Botble\Dashplugin\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Dashplugin\Forms\CustomerForm;
use Botble\Dashplugin\Http\Requests\CustomerCreateRequest;
use Botble\Dashplugin\Http\Requests\CustomerEditRequest;
use Botble\Dashplugin\Models\Customer;
use Botble\Dashplugin\Tables\CustomerTable;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class TableFrontController extends BaseController
{
    
    
    public function __construct()
    {
    }

    public function index(CustomerTable $table)
    {

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/dashplugin::customer.create'));

        return CustomerForm::create()->renderForm();
    }

    public function store(CustomerCreateRequest $request)
    {
        $form = CustomerForm::create();
        $form->saving(function (CustomerForm $form) use ($request) {
            $form
                ->getModel()
                ->fill([
                    ...$request->validated(),
                    'password' => Hash::make($request->input('password')),
                ])
                ->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('customer.index'))
            ->setNextUrl(route('customer.edit', $form->getModel()->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Customer $customer)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $customer->name]));

        Assets::addScriptsDirectly('vendor/core/plugins/hotel/js/customer.js');

        $customer->password = null;

        return CustomerForm::createFromModel($customer)->renderForm();
    }

    public function update(Customer $customer, CustomerEditRequest $request)
    {
        CustomerForm::createFromModel($customer)
            ->saving(function (CustomerForm $form) use ($request) {
                $data = Arr::except($request->validated(), 'password');

                if ($request->input('is_change_password') == 1) {
                    $data['password'] = Hash::make($request->input('password'));
                }

                $form
                    ->getModel()
                    ->fill($data)
                    ->save();
            });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('customer.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Customer $customer)
    {
        return DeleteResourceAction::make($customer);
    }
}
