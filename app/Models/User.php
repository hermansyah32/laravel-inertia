<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\EmailVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

enum UserStatus: string
{
    case ACTIVE = "active";
    case SUSPEND = "suspend";
    case BANNED = "banned";
}

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

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

    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerification());
    }

    // /**
    //  * The "booted" method of the model.
    //  *
    //  * @return void
    //  */
    // protected static function booted()
    // {
    //     static::creating(function (User $user) {
    //         try {
    //             UserProfile::create(['user_id' => $user->id]);
    //         } catch (\Throwable $th) {
    //             echo ($th->getMessage());
    //             return false;
    //         }
    //     });
    // }
}
