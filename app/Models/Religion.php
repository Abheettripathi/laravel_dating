<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    use HasFactory;
    
    protected $table = 'religions';

    protected $fillable = [
        'name',
    ];

    public $timestamps = true;

    /**
     * Get the users associated with the religion.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'religion_id');
    }
}
