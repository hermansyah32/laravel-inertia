<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\AccountActivatedNotification;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\UserLoginNotification;
use App\Notifications\UserResetNotification;
use App\Notifications\VerifyEmailNotification;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Spatie\Permission\Traits\HasRoles;

enum UserStatus: string
{
    case ACTIVE = "active";
    case SUSPEND = "suspend";
    case BANNED = "banned";
}

class User extends Authenticatable
{
    use AuthenticationLoggable, HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    // protected $with = ['profile', 'roles'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'first_profile',
        'status'
    ];

    /**
     * Searchable data
     */
    protected $searchable = [
        'name',
        'email',
        'username',
        'status',
        'role',
        'profile_gender',
        'profile_photo_url',
        'profile_phone',
        'profile_birthday',
        'profile_address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        // Return email address and name...
        return [$this->email => $this->name];
    }

    /**
     * Profile relation
     * @return HasOne 
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    /**
     * Get profile photo url
     * @return string|null
     */
    public function ProfilePhoto()
    {
        if (!filter_var($this->Profile->photo_url, FILTER_VALIDATE_URL) === false) {
            return $this->Profile->photo_url;
        } else {
            return '';
        }
    }

    /**
     * Send the verified notification.
     * 
     * @return void 
     */
    public function sendVerifiedNotification()
    {
        $this->notify(new AccountActivatedNotification());
    }

    /**
     * Reset password change notification.
     * 
     * @param string $ipAddress
     * @param mixed $ipLocation
     * @return void 
     */
    public function sendResetNotification($rawPassword)
    {
        $this->notify(new UserResetNotification($rawPassword));
    }

    /**
     * Send password change notification.
     * 
     * @param string $ipAddress
     * @param mixed $ipLocation
     * @return void 
     */
    public function sendPasswordChangeNotification($ipAddress, $ipLocation)
    {
        $this->notify(new UserLoginNotification($ipAddress, $ipLocation));
    }

    /**
     * Send the verification notification.
     * 
     * @return void 
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
        static::created(function (User $user) {
            try {
                UserProfile::create(['user_id' => $user->id]);
            } catch (\Throwable $th) {
                echo ($th->getMessage());
                return false;
            }
        });
    }
}
