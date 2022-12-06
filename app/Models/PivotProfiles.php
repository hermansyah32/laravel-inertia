<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PivotProfiles extends Pivot
{
    protected $table = 'user_has_profiles';

    public $timestamps = false;
    
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

    // protected $with = ['default', 'teacher', 'student', 'studentParent'];

    public function profile()
    {
        return $this->morphTo();
    }

    public function default()
    {
        return $this->hasOne(UserProfile::class, 'id', 'profile_id');
    }

    public function teacher()
    {
        return $this->hasOne(TeacherProfile::class, 'id', 'profile_id');
    }

    public function student()
    {
        return $this->hasOne(StudentProfile::class, 'id', 'profile_id');
    }

    public function studentParent()
    {
        return $this->hasOne(StudentParent::class, 'id', 'profile_id');
    }
}
