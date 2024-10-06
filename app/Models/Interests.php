<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interests extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'name'];
    protected $table = 'interests'; // If the table name is 'users'
//mapping
     public function users()
    {
        return $this->hasMany(User::class, 'id');
    }
    
}
