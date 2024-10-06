<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer1 extends Model
{
    protected $table = 'answers1';

    protected $fillable = [
        'question_id',
        'user_id',
        'is_correct',
    ];

    public function user()
    {
        return $this->belongsTo(Answer::class);
    }

    public function question()
    {
        return $this->belongsTo(Quiz::class);
    }
}