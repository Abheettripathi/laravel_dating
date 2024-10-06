<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';

    // Allow mass assignment for the specified attributes
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    // Disable timestamps if not using them
    public $timestamps = false;
}
