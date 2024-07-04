<?php

namespace Botble\Dashplugin\Http\Controllers\Front\Customers;

use Botble\ACL\Traits\RegistersUsers;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Dashplugin\Facades\DashHelper as HotelHelper;
use Botble\Dashplugin\Forms\Fronts\Auth\RegisterForm;
use Botble\Dashplugin\Http\Requests\RegisterRequest;
use Botble\Dashplugin\Models\Customer;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    use RegistersUsers;

    protected string $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('customer.guest');
    }

    public function showRegistrationForm()
    {
        SeoHelper::setTitle(__('Register'));

        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add(__('Register'), route('customer.register'));

        if (! session()->has('url.intended') &&
            ! in_array(url()->previous(), [route('customer.login'), route('customer.register')])
        ) {
            session(['url.intended' => url()->previous()]);
        }

        return Theme::scope(
            'customer.register',
            ['form' => RegisterForm::create()]
        )->render();
    }

    public function register(RegisterRequest $request, BaseHttpResponse $response)
    {
        $this->validator($request->input())->validate();

        do_action('customer_register_validation', $request);

        $customer = $this->create($request->input());

        event(new Registered($customer));

        if (HotelHelper::isEnableEmailVerification()) {
            $this->registered($request, $customer);

            return $response
                    ->setNextUrl(route('customer.login'))
                    ->setMessage(__('We have sent you an email to verify your email. Please check and confirm your email address!'));
        }

        $customer->confirmed_at = Carbon::now();
        $customer->save();

        $this->guard()->login($customer);

        return $response->setNextUrl($this->redirectPath())->setMessage(__('Registered successfully!'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, (new RegisterRequest())->rules());
    }

    protected function create(array $data)
    {
        return Customer::query()->create([
            'first_name' => BaseHelper::clean($data['first_name']),
            'last_name' => BaseHelper::clean($data['last_name']),
            'email' => BaseHelper::clean($data['email']),
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function guard()
    {
        return auth('customer');
    }

    public function confirm(int|string $id, Request $request, BaseHttpResponse $response)
    {
        if (! URL::hasValidSignature($request)) {
            abort(404);
        }

        $customer = Customer::query()->findOrFail($id);

        $customer->confirmed_at = Carbon::now();
        $customer->save();

        $this->guard()->login($customer);

        return $response
            ->setNextUrl(route('customer.dashboard'))
            ->setMessage(__('You successfully confirmed your email address.'));
    }

    public function resendConfirmation(
        Request $request,
        BaseHttpResponse $response
    ) {
        $customer =Customer::query()->where('email', $request->input('email'))->first();

        if (! $customer) {
            return $response
                ->setError()
                ->setMessage(__('Cannot find this customer!'));
        }

        $customer->sendEmailVerificationNotification();

        return $response
            ->setMessage(__('We sent you another confirmation email. You should receive it shortly.'));
    }
}
