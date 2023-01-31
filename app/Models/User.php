<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\AccountActivatedNotification;
use App\Notifications\EmailChangeNotification;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\UserLoginNotification;
use App\Notifications\UserResetNotification;
use App\Notifications\VerifyEmailNotification;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
     * Generate a new UUID for the model.
     *
     * @return string
     */
    public function newUniqueId()
    {
        return (string) Str::orderedUuid();
    }

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
     * @return HasOneThrough
     */
    public function profile(): HasOneThrough
    {
        return $this->hasOneThrough(UserProfile::class, PivotProfiles::class, 'user_id', 'id', 'id', 'profile_id');
    }

    /**
     * Get all related profile
     * TODO: still not working, pivot successfully but not showing data
     */
    public function profiles()
    {
        return $this->hasMany(PivotProfiles::class)->with('profile');
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
     * Send email change notification
     * 
     * @param mixed $token 
     * @return void 
     */
    public function sendEmailChangeNotification($token){
        // $this->notify(new EmailChangeNotification($token));
    }

    /**
     * Reset password change notification by admin.
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
            $userUuid = $model->newUniqueId();
            $model->{$model->getKeyName()} = $userUuid;
        });
        static::created(function ($model) {
            try {
                $defaultProfile = UserProfile::create();
                DB::table('user_has_profiles')->insert(['user_id' => $model->id, 'profile_type' => UserProfile::class, 'profile_id' => $defaultProfile->id]);
            } catch (\Throwable $th) {
                if (config('app.debug')) dd($th);
                return false;
            }
        });
    }
}
