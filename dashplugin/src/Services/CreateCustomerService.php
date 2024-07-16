<?php

namespace Botble\Dashplugin\Services;

use Botble\Dashplugin\Events\RoleAssignmentEvent;
use Botble\Dashplugin\Models\CustomerRole;
use Botble\Dashplugin\Models\Customer;
use Botble\Support\Services\ProduceServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CreateCustomerService implements ProduceServiceInterface
{
    public function __construct()
    {
    }

    public function execute(Request $request): Customer
    {
        $user = new Customer();
        $user->fill($request->input());
        $user->password = Hash::make($request->input('password'));
        $user->save();

        if($roleId = $request->input('role_id') ) {

            $role = CustomerRole::query()->find($roleId);
        }else{
            $roleDefault = CustomerRole::query()->where('is_default', 1)->first();
            $role = $roleDefault;
        }

        if ( $role ) {
            /**
             * @var Role $role
             */
            $role->users()->attach($user->getKey());

            event(new RoleAssignmentEvent($role, $user));
            
        }

        return $user;
    }
}
