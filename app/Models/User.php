<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // Define the table associated with the model
    protected $table = 'users';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'state',
        'city',
        'phone',
        'gender',
        'country',
        'tribes',
        'looking_for',
        'religion',
        'qualifications',
        'about',
        'interest',
        'date_of_birth',
        'lat',
        'lang'
    ];

    // Enable timestamps for the model
    public $timestamps = true;

    // JWT functions: return the primary key for JWT identifier
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // JWT functions: return custom claims (none in this case)
    public function getJWTCustomClaims()
    {
        return [];
    }

    // Define relationship to Interests model (One user can have many interests)
    public function interest()
    {
        return $this->hasMany(Interests::class, 'id', 'interest');
    }

    // Define relationship to Country model (One user can belong to many countries)
    public function country()
    {
        return $this->hasMany(Country::class, 'id', 'country');
    }

    // Define relationship to City model (One user can belong to many cities)
    public function city()
    {
        return $this->hasMany(City::class, 'id', 'city');
    }

    // Define relationship to Religion model (One user can belong to many religions)
    public function religion()
    {
        return $this->hasMany(City::class, 'id', 'religion');
    }

    // Define the relationship for blocked users (many-to-many relationship between users)
    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'user_id', 'blocked_user_id');
    }
}
