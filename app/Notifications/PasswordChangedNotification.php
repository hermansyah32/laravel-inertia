<?php

namespace App\Notifications;

use App\Helper\NotificationType;
use App\Helper\UserNotification;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class PasswordChangedNotification extends Notification
{
    /**
     * Activity IP address
     * 
     * @var string
     */
    public $ipAddress;

    /**
     * Create a notification instance.
     *
     * @param  string  $ip
     * @return void
     */
    public function __construct($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

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
            ->subject(Lang::get('Password Changed'))
            ->greeting(Lang::get('Hello :name!', ['name' => $notifiable->name]))
            ->line(Lang::get('We have detected password change request. If this is not you, please change your password soon or contact support.'))
            ->markdown(
                'mail.user-events.login',
                [
                    'ipLocation' => Lang::get('Unknown'),
                    'timeLogin' => Carbon::now()->format('dd/MM/YYYY HH:mm:ss')
                ]
            )
            ->line(Lang::get('If this is you, no further action is required.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return UserNotification::create(
            NotificationType::WARNING,
            'Password Changed Notification',
            "Hi !\nWe have detected a new password change request.",
            [
                'ipLocation' => Lang::get('Unknown'),
                'timeLogin' => Carbon::now()->format('dd/MM/YYYY HH:mm:ss')
            ]
        );
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
