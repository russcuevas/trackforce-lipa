<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class InvestigatorResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('TrackForce Lipa — Password Reset Request')
            ->view('emails.reset_password', [
                'url'  => $url,
                'name' => $notifiable->full_name ?? 'Investigator',
            ]);
    }
}
