<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    // Specify the database table associated with this model
    protected $table = 'results';

    // Define which attributes are mass-assignable
    protected $fillable = [
        'auth_user_id',
        'challenge_user_id',
        'auth_user_correct_answers',
        'challenge_user_correct_answers',
        'winner',
    ];

    /**
     * Get the auth user associated with the result.
     */
    public function authUser()
    {
        // Define a one-to-many inverse relationship with the Answer model
        return $this->belongsTo(Answer::class, 'auth_user_id');
    }

    /**
     * Get the challenge user associated with the result.
     */
    public function challengeUser()
    {
        // Define a one-to-many inverse relationship with the Answer model
        return $this->belongsTo(Answer::class, 'challenge_user_id');
    }
}
