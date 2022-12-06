<?php

namespace App\Models;

use Database\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Permission\Contracts\Permission as ContractsPermission;
use Spatie\Permission\Models\Permission as ModelPermission;

class Permission extends ModelPermission implements ContractsPermission
{
    /**
     * user id change to uuid. Spatie role reference 
     * https://petersowah.dev/using-uuids-with-spatie-laravel-permissions-package
     */

    use SoftDeletes, Uuid;

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
        'name',
        'guard_name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string'
    ];
}
