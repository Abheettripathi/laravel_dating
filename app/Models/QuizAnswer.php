<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;
    protected $table ='quiz_answers';
    protected $fillable = [
        'room_id', 'question_id', 'user_id', 'answer', 'original_answer',
    ];
}
