<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $table='promo_codes';

    protected $fillable=[
        'code',
        'type',
        'code_for',
        'discount_amount',
        'upto_discount_amount',
        'from_date',
        'to_date'
    ];
    public $timestamps =true;
}
