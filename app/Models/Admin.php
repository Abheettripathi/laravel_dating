<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $table='admins';
    protected $fillable=[

        'name',
        'email',
        'mobile',
        'password',
        'password1',
        'access_module',
        'type'
    ];
    public $timestamps =true;

}
