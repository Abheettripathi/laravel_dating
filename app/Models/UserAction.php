<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    use HasFactory;

    // Define the fillable attributes for mass assignment
    protected $fillable = ['user_id', 'target_user_id', 'action'];

    // Define a relationship to the User model (for the user who performed the action)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define a relationship to the User model (for the target user of the action)
    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
