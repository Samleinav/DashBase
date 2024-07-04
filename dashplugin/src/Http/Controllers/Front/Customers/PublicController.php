<?php

namespace Botble\Dashplugin\Http\Controllers\Front\Customers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Dashplugin\Facades\DashHelper;
use Botble\Dashplugin\Http\Requests\AvatarRequest;
use Botble\Dashplugin\Http\Requests\EditAccountRequest;
use Botble\Dashplugin\Http\Requests\UpdatePasswordRequest;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Services\ThumbnailService;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class PublicController extends BaseController
{
    public function __construct()
    {
               
    }

    public function getIndex()
    {
        SeoHelper::setTitle(__('Account information'));

        return Theme::scope('customer.account.edit-account', [])
        ->render();
    }

    public function getEditAccount()
    {
        SeoHelper::setTitle(__('Profile'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Profile'), route('customer.edit-account'));

        return Theme::scope('customer.account.edit-account', [])
            ->render();
    }

    public function postEditAccount(EditAccountRequest $request, BaseHttpResponse $response)
    {
        $customer = auth('customer')->user();

        $customer->fill($request->except('email'));

        $customer->dob = Carbon::parse($request->input('dob'))->toDateString();

        $customer->save();

        do_action(HANDLE_CUSTOMER_UPDATED_DASH, $customer, $request);

        return $response
            ->setNextUrl(route('customer.edit-account'))
            ->setMessage(__('Update profile successfully!'));
    }

    public function getChangePassword()
    {
        SeoHelper::setTitle(__('Change Password'));

        Theme::breadcrumb()->add(__('Home'), route('public.index'))
            ->add(__('Change Password'), route('customer.change-password'));

        return Theme::scope(
            'customer.account.change-password',
            []
        )->render();
    }

    public function postChangePassword(UpdatePasswordRequest $request, BaseHttpResponse $response)
    {
        $currentUser = auth('customer')->user();

        $currentUser->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return $response->setMessage(__('Updated password successfully!'));
    }

    public function postAvatar(AvatarRequest $request, ThumbnailService $thumbnailService, BaseHttpResponse $response)
    {
        try {
            $account = auth('customer')->user();

            $result = RvMedia::handleUpload($request->file('avatar_file'), 0, $account->upload_folder);

            if ($result['error']) {
                return $response->setError()->setMessage($result['message']);
            }

            $avatarData = json_decode($request->input('avatar_data'));

            $file = $result['data'];

            $thumbnailService
                ->setImage(RvMedia::getRealPath($file->url))
                ->setSize((int) $avatarData->width, (int) $avatarData->height)
                ->setCoordinates((int) $avatarData->x, (int) $avatarData->y)
                ->setDestinationPath(File::dirname($file->url))
                ->setFileName(File::name($file->url) . 'Front' . File::extension($file->url))
                ->save('crop');

            $account->avatar = $file->url;
            $account->save();

            return $response
                ->setMessage(trans('plugins/customer::dashboard.update_avatar_success'))
                ->setData(['url' => RvMedia::url($file->url)]);
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
