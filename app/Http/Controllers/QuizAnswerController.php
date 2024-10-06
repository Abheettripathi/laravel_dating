<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizAnswer;

class QuizAnswerController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'room_id' => 'required|exists:rooms,id',
             'question_id' => 'required|exists:answers,id',
            'user_id' => 'required|exists:users,id',
            'answer' => 'required|string',
            'original_answer' => 'required|string',
        ]);

        $quizAnswer = QuizAnswer::create([
            'room_id' => $validatedData['room_id'],
            'question_id' => $validatedData['question_id'],
            'user_id' => $validatedData['user_id'],
            'answer' => $validatedData['answer'],
            'original_answer' => $validatedData['original_answer'],
        ]);

        return response()->json($quizAnswer, 201);
    }
}
