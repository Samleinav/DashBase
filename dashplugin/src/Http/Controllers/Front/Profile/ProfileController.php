<?php

namespace Botble\Dashplugin\Http\Controllers\Front\Profile;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Dashplugin\Facades\DashHelper;
use Botble\Dashplugin\Http\Requests\AvatarRequest;
use Botble\Dashplugin\Http\Requests\EditAccountRequest;
use Botble\Dashplugin\Http\Requests\UpdatePasswordRequest;
use Botble\Media\Services\ThumbnailService;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Botble\Media\Facades\RvMedia;
use Botble\Dashplugin\Forms\Fronts\ProfileForm;
use Illuminate\Support\Arr;
use Botble\Base\Supports\Breadcrumb;

use Illuminate\Support\Facades\Route;
class ProfileController extends BaseController
{

    protected function breadcrumb(): Breadcrumb
    {
       return DashHelper::breadcrumb()
        ->add(__('Home'), route('public.index'))
        ->add(__('Profile'), route('public.user.profile'));
    }

    
    public function getIndex()
    {
        SeoHelper::setTitle(__('Account information'));

        $customer = auth('customer')->user();

        $customer->password = null;
        $this->breadcrumb()->add(__('Profile'));

        return Theme::scope('customer.account.profile', [])
        ->render();
    }

    public function getEditAccount()
    {
        SeoHelper::setTitle(__('Profile'));

        DashHelper::breadcrumb()
            ->add(__('Profile'), route('public.user.profile'))
            ->add(__('Edit'), route('public.user.edit-account'));

            $customer = auth('customer')->user();

            $customer->password = null;
            
        return ProfileForm::createFromModel($customer)->renderFront();

    }

    public function postEditAccount(EditAccountRequest $request, BaseHttpResponse $response)
    {
        $customer = auth('customer')->user();
        ProfileForm::createFromModel($customer)
            ->saving(function (ProfileForm $form) use ($request) {
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

        return $response
            ->setNextUrl(route('public.user.edit-account'))
            ->setMessage(__('Update profile successfully!'));
    }
    public function updateProfile(EditAccountRequest $request, BaseHttpResponse $response)
    {
        $customer = auth('customer')->user();
        ProfileForm::createFromModel($customer)
            ->saving(function (ProfileForm $form) use ($request) {
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

        return $response
            ->setNextUrl(route('public.user.edit-account'))
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