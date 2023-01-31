<?php

namespace App\Models;

use App\Helper\Constants;
use Database\Traits\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes, Uuid;

    /**
     * @var string CommonDateFormat
     */
    const CommonDateFormat = "Y-m-d";

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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'photo_url',
        'gender',
        'address',
        'phone',
        'birthday'
    ];

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->using(PivotProfiles::class);
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? config('app.url') . '/uploads/' . $value : $value,
            set: fn ($value) => $value
        );
    }

    public function getConstants()
    {
        return [
            'gender' => Constants::GENDER(),
        ];
    }

    public function updateRules()
    {
        return [
            'name' => 'required',
            'profile_gender' => ['nullable', 'string', 'in:male,female'],
            'profile_birthday' => 'nullable|date_format:' . self::CommonDateFormat,
            'profile_address' => 'nullable',
            'profile_photo_url' => 'nullable|file|max:5120|mimes:jpg,png,jpeg',
            'profile_phone' => 'nullable',
        ];
    }

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
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        parent::boot();
        static::creating(function ($model) {
            $profileUuid = $model->newUniqueId();
            $model->{$model->getKeyName()} = $profileUuid;
        });
    }
}
