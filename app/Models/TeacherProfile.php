<?php

namespace App\Models;

use App\Helper\Constants;
use Database\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherProfile extends Model
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
        'teacher_number',
        'education',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->morphOne(User::class, 'user');
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
            'profile_teacher_number' => ['nullable', 'string'],
            'profile_education' => ['nullable', 'json'],
        ];
    }
}
