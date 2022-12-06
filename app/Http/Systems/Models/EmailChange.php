<?php

namespace App\http\Systems\Models;

use Illuminate\Database\Eloquent\Model;

class EmailChange extends Model
{
    protected $fillable = [
        'email', 'new_email', 'token', 'created_at'
    ];
}
