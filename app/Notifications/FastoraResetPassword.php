<?php

namespace App\Notifications;

use Filament\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Branded replacement for Filament's default password-reset email. Bound in
 * place of Filament\Auth\Notifications\ResetPassword in AppServiceProvider,
 * so Filament's own request/reset flow (rate limiting, token/URL generation)
 * is untouched — only the email's look is ours.
 */
class FastoraResetPassword extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset your Fastora password')
            ->view('emails.reset-password', ['url' => $this->url]);
    }
}
