<?php

namespace App\Notifications;

use App\Helper\NotificationType;
use App\Helper\UserNotification;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class UserLoginNotification extends Notification
{
    /**
     * Activity IP address
     * 
     * @var string
     */
    public $ipAddress;

    /**
     * Activity IP address location
     * 
     * @var mixed
     */
    public $ipLocation;

    /**
     * Create a notification instance.
     *
     * @param  string  $ip
     * @param  string  $ipLocation
     * @return void
     */
    public function __construct($ipAddress, $ipLocation)
    {
        $this->ipAddress = $ipAddress;
        $this->ipLocation = $ipLocation;
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
            ->subject(Lang::get('New Login Detected'))
            ->greeting(Lang::get('Hello :name!', ['name' => $notifiable->name]))
            ->line(Lang::get('We have detected a new login activity from your account. If this is not you, please change your password soon.'))
            ->line(Lang::get('Now you can explore our application. Please click the button below and enjoy our application.'))
            ->markdown(
                'mail.user-events.login',
                [
                    'ipAddress' => $this->ipAddress,
                    // 'ipLocation' => !$this->ipLocation ? Lang::get('Unknown') : $this->ipLocation->location,
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
            'Sign-In Notification',
            "Hi !\nWe have detected a new login activity.",
            [
                'ipAddress' => $this->ipAddress,
                // 'ipLocation' => !$this->ipLocation ? Lang::get('Unknown') : $this->ipLocation->location,
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
