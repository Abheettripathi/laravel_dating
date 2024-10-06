<?php
namespace App\Services;

use App\Models\Answer;
use Illuminate\Support\Facades\Log;

class WinnerService
{
    
    public function determineWinnerByQuestionId($questionId)
    {
        // Correct column name is 'question_id'
        $answers = Answer::where('question_id', $questionId)->get();

        if ($answers->isEmpty()) {
            return ['error' => 'No answers found for the given question ID'];
        }

        foreach ($answers as $answer) {
            $authUserAnswer = $answer->auth_user_answer;
            $challengeUserAnswer = $answer->challenge_user_answer;
            $originalAnswer = $answer->original_answer;

            Log::info('Answers:', [
                'auth_user_id' => $answer->auth_user_id,
                'challenge_user_id' => $answer->challenge_user_id,
                'authUserAnswer' => $authUserAnswer,
                'challengeUserAnswer' => $challengeUserAnswer,
                'originalAnswer' => $originalAnswer,
            ]);

            $authUserCorrect = $authUserAnswer === $originalAnswer;
            $challengeUserCorrect = $challengeUserAnswer === $originalAnswer;

            if ($authUserCorrect && $challengeUserCorrect) {
                return [
                    'winner' => 'Both users answered correctly',
                    'auth_user_id' => $answer->auth_user_id,
                    'challenge_user_id' => $answer->challenge_user_id
                ];
            } elseif ($authUserCorrect) {
                return [
                    'winner' => 'Auth user',
                    'auth_user_id' => $answer->auth_user_id
                ];
            } elseif ($challengeUserCorrect) {
                return [
                    'winner' => 'Challenge user',
                    'challenge_user_id' => $answer->challenge_user_id
                ];
            }
        }

        return ['winner' => 'None'];
    }
}
