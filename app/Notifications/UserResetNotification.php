<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class UserResetNotification extends Notification
{
    private $rawPassword;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($rawPassword)
    {
        $this->rawPassword = $rawPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->greeting(Lang::get('Hello :name!', ['name' => $notifiable->name]))
            ->line(Lang::get('You are receiving this email because your account is reset by admin.'))
            ->line(Lang::get('This is your new password: ') . $this->rawPassword)
            ->line(Lang::get('If this request is not valid, please contact administrator.'));
    }
}
