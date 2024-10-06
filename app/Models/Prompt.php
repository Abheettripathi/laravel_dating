<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
    use HasFactory;

    protected $table = 'prompts';
    
    protected $fillable = [
        'country_id',
        'state_id',
        'prompt'
    ];
    
    public $timestamps = true;
}
