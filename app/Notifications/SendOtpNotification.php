<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendOtpNotification extends Notification
{
    protected $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Login OTP Code')
            ->greeting('Hello!')
            ->line('Here is your one-time password (OTP):')
            ->line("**{$this->otp}**")
            ->line('This code will expire in 5 minutes.')
            ->line('If you did not try to log in, please ignore this message.');
    }
}
