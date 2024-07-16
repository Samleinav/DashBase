<?php

namespace Botble\Dashplugin\Http\Controllers\Front\Settings;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Dashplugin\Facades\DashHelper;
use Botble\Dashplugin\Forms\CustomerForm;
use Botble\Dashplugin\Http\Requests\CustomerCreateRequest;
use Botble\Dashplugin\Http\Requests\CustomerEditRequest;
use Botble\Dashplugin\Models\Customer;
use Botble\Dashplugin\Models\CustomerRole;
use Botble\Dashplugin\Tables\CustomerTable;
use Botble\Dashplugin\Services\CreateCustomerService;
use Botble\Dashplugin\Events\RoleAssignmentEvent;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Models\MediaFile;
use Botble\Base\Supports\Breadcrumb;

class CustomerController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
          return  DashHelper::breadcrumb()
            ->add(__('Settings'), route('public.settings.index'))
            ->add(trans("Users"), route('public.settings.customers.index'));
    }

    public function index(CustomerTable $table)
    {
        $this->pageTitle(trans('Users'));
        //Theme::layout('test');
        return $table->renderTable([],[],true);
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/dashplugin::customer.create'));
        
        return CustomerForm::create()->renderFront();
    }

    public function store(CustomerCreateRequest $request, CreateCustomerService $service )
    {
        $form = CustomerForm::create();
        $customer = null;
        $form->saving(function (CustomerForm $form) use ($request, $service, &$customer) {

            $customer = $service->execute($request);

            if ($request->hasFile('avatar_input')){
                $result = RvMedia::uploadFromBlob($request->file('avatar_file'), folderSlug: 'users');
    
                if ($result['error']) {
                    return $this
                        ->httpResponse()->setError()->setMessage($result['message']);
                }
    
                $file = $result['data'];
    
                $mediaFile = MediaFile::query()->find($customer->avatar_id);
                $mediaFile?->delete();
    
                $customer->avatar_id = $file->id;
            }

            $form->setupModel($customer);

        });

        
        

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('public.settings.customers.index'))
            ->setNextUrl(route('public.settings.customers.edit', $form->getModel()->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Customer $customer)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $customer->name]));

        $customer->password = null;

        return CustomerForm::createFromModel($customer)->renderFront();
    }

    public function update(Customer $customer, CustomerEditRequest $request)
    {
        CustomerForm::createFromModel($customer)
            ->saving(function (CustomerForm $form) use ($request) {
                $data = Arr::except($request->validated(), 'password');

                if ($request->input('is_change_password') == 1) {
                    $data['password'] = Hash::make($request->input('password'));
                }
                //check if avatar is uploaded
                if ($request->hasFile('avatar_input')) {

                    $result = RvMedia::uploadFromBlob($request->file('avatar_input'), folderSlug: 'users');

                    if ($result['error']) {
                        return $this
                            ->httpResponse()->setError()->setMessage($result['message']);
                    }

                    $file = $result['data'];


                    $data['avatar'] = $file->url;
                }
                

                $form
                    ->getModel()
                    ->fill($data)
                    ->save();
            });

            $role = CustomerRole::query()->find($request->input('roles'));
            event(new RoleAssignmentEvent($role, $customer));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('public.settings.customers.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Customer $customer)
    {
        return DeleteResourceAction::make($customer);
    }

    
}
