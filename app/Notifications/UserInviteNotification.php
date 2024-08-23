<?php

namespace App\Notifications;

use App\Mail\InviteMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInviteNotification extends Notification
{
    use Queueable;

    public $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function via(object $notifiable): array
    {
        return [
            MailChannel::class,
            DatabaseChannel::class
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->view('mails.invite-mail', [
                'user' => $notifiable,
                'password' => $this->password,
            ])
            ->subject('HR Davetiye');
    }


    public function toArray(object $notifiable)
    {
        return [
            'password' => $this->password,
            'email' => $notifiable->email,
        ];
    }

    public function databaseType()
    {
        return 'invite';
    }
}
