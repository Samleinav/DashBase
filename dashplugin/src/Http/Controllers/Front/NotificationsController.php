<?php

namespace Botble\Dashplugin\Http\Controllers\Front;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Botble\Dashplugin\Http\Requests\Fronts\NotificationRequest;
class NotificationsController extends BaseController
{

    protected function breadcrumb(): Breadcrumb
    {
        return (new Breadcrumb())
            ->add(__('Home'), route('public.index'))
            ->add(__('Notifications'));
    }


    public function index(NotificationTable $dataTable){

        SeoHelper::setTitle(__('Notifications'));
        Theme::breadcrumb()->add(__('Notifications'));

        return $dataTable->renderTable();
    }

    public function create(){

        $this->pageTitle(trans('plugins/dashplugin::notification.create'));

        return NotificationForm::create()->renderFront();
    }


    public function store(NotificationRequest $request){
        
        $notification = NotificationForm::create();

        $notification->save();

        return $this->httpResponse()
        ->setPreviousUrl(route('public.settings.notifications'))
        ->setNextUrl(route('public.settings.notifications.edit', $notification->getModel()->getKey()))
        ->setMessage(trans('core/base::notices.create_success_message'));
    }


    public function edit(Notification $notification){

        $this->pageTitle(trans('plugins/dashplugin::notification.edit'));

        return NotificationForm::create($notification)->renderFront();
    }


    public function update(NotificationRequest $request, Notification $notification){

        $notification->fill($request->input());
        $notification->save();

        return $this->httpResponse()
        ->setPreviousUrl(route('public.settings.notifications'))
        ->setNextUrl(route('public.settings.notifications.edit', $notification->getModel()->getKey()))
        ->setMessage(trans('core/base::notices.update_success_message'));
    }


    public function destroy(Notification $notification){

        $notification->delete();

        return $this
        ->httpResponse()
        ->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * Post ajax
     */
    public function markAllAsRead(NotificationRequest $request ,
    BaseHttpResponse $response)
    {
        $user = $request->user('customer');

        // Marcar las notificaciones directas como leídas
        $user->notifications()
             ->where('status', 'unread')
             ->update(['status' => 'read']);

        // Marcar las notificaciones globales como leídas
        $user->globalNotifications()
             ->wherePivot('is_read', false)
             ->update(['is_read' => true]);

            return $response->withUpdatedSuccessMessage();
    }
}
