<?php

namespace App\Notifications;

use App\Helper\NotificationType;
use App\Helper\UserNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class AccountActivatedNotification extends Notification
{
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return $this->buildMailMessage($notifiable);
    }

    /**
     * Get the activated email notification mail message.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Account Activated'))
            ->greeting(Lang::get('Welcome :name!', ['name' => $notifiable->name]))
            ->line(Lang::get('Thank you for using our application.'))
            ->line(Lang::get('Now you can explore our application. Please click the button below and enjoy our application.'))
            ->action(Lang::get('Get started'), url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return UserNotification::create(NotificationType::INFO, 'Welcome', "Hi! \n Thanks for joining us.");
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
