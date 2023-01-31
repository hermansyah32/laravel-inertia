<?php

namespace App\Http\Systems\Models;

use Illuminate\Database\Eloquent\Model;

class EmailChange extends Model
{
    protected $fillable = [
        'email', 'new_email', 'token', 'created_at'
    ];

    // Disable timestamps
    public $timestamps = false;
}
