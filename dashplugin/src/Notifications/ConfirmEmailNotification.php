<?php

namespace Botble\Dashplugin\Notifications;

use Botble\Base\Facades\EmailHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

class ConfirmEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $emailHandler = EmailHandler::setModule(DASH_MODULE_SCREEN_NAME)
            ->setType('plugins')
            ->setTemplate('confirm-email')
            ->addTemplateSettings(DASH_MODULE_SCREEN_NAME, config('plugins.dashplugin.email', []))
            ->setVariableValue('verify_link', URL::signedRoute('customer.confirm', ['user' => $notifiable->id]));

        return (new MailMessage())
            ->view(['html' => new HtmlString($emailHandler->getContent())])
            ->subject($emailHandler->getSubject());
    }
}
