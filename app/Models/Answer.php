<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'question_id',
        'auth_user_id',
        'challenge_user_id',
        'auth_user_answer',
        'challenge_user_answer',
        'original_answer',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'question_id');
    }

    public function authUser()
    {
        return $this->belongsTo(User::class, 'auth_user_id');
    }

    public function challengeUser()
    {
        return $this->belongsTo(User::class, 'challenge_user_id');
    }
}
