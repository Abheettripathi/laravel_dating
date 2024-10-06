<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regional_group extends Model
{
    use HasFactory;
    Protected $table='groups';
    Protected $fillable=[
        'group_image',
    	'country_id',	
        'state_id',	
        'group_name',
        	'user_id',	
            'is_block',
    ];
    public $timestamps =true;

}
