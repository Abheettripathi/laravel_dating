<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    // Define the fields that can be mass assigned
    protected $fillable = [
        'interest_id',  // Foreign key that links the quiz to an interest
        'question_id',  // Foreign key that links the quiz to a specific question
        'option_1',     // First multiple choice option
        'option_2',     // Second multiple choice option
        'option_3',     // Third multiple choice option
        'option_4',     // Fourth multiple choice option
        'answer',       // Correct answer to the question
        'status',       // Status of the quiz (e.g., active or inactive)
    ];

    // Relationship with Answer model (a quiz can have many answers)
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
