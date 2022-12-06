<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

class Student extends User
{
    use AuthenticationLoggable, HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /** @var string */
    protected $table = 'users';

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
     * The "boot" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
        static::addGlobalScope(function ($models) {
            $models->with("roles")->whereHas("roles", function ($q) {
                $q->whereIn("name", ["student"]);
            });
        });
        static::created(function (User $user) {
            try {
                StudentProfile::create(['user_id' => $user->id]);
            } catch (\Throwable $th) {
                echo ($th->getMessage());
                return false;
            }
        });
    }

    public function getMorphClass()
    {
        return 'App\Models\User';
    }
}
