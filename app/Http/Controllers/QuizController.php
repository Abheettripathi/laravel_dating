<?php
namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Models\Answer1;
use App\Models\Result;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    public function index()
    {
        return response()->json(Quiz::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'interest_id' => 'required|integer',
            'question' => 'required|string|unique:quizzes,question',
            'option_1' => 'required|string',
            'option_2' => 'required|string',
            'option_3' => 'nullable|string',
            'option_4' => 'nullable|string',
            'answer' => 'required|string|in:' . implode(',', [$request->option_1, $request->option_2, $request->option_3, $request->option_4]),
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $data = $request->all();
        $data['status'] = $data['status'] ?? 'inactive';

        $quiz = Quiz::create($data);

        return response()->json($quiz, 201);
    }

    public function show($id)
    {
        return response()->json(Quiz::findOrFail($id), 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'interest_id' => 'sometimes|integer',
            'question' => 'sometimes|string|unique:quizzes,question,' . $id,
            'option_1' => 'sometimes|string',
            'option_2' => 'sometimes|string',
            'option_3' => 'nullable|string',
            'option_4' => 'nullable|string',
            'answer' => 'sometimes|string|in:' . implode(',', [$request->option_1, $request->option_2, $request->option_3, $request->option_4]),
            'status' => 'nullable|string|in:active,inactive',
        ]);
        $quiz = Quiz::findOrFail($id);
        $quiz->update($request->all());
        return response()->json($quiz, 200);
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return response()->json(null, 204);
    }

    public function random()
    {
        Log::info('Attempting to retrieve a random quiz.');
    
        // Debugging: Retrieve all quizzes
        $quizzes = Quiz::all();
        // dd($quizzes);  // Remove or comment this line after debugging
    
        // Retrieve a random quiz from the database
        $quiz = Quiz::inRandomOrder()->first();
    
        // Check if a quiz was found
        if ($quiz) {
            Log::info('Quiz found: ', ['quiz' => $quiz]);
            return response()->json($quiz, 200); // Return the quiz with a 200 status code
        } else {
            Log::info('No quizzes found');
            return response()->json([
                'message' => 'No quizzes found'
            ], 404); // Return a 404 status code if no quizzes are found
        }
    }

    public function getTopScorer()
    {
        // Calculate correct answers for each user
        $authUserScores = Result::select('auth_user_id as user_id', DB::raw('SUM(CASE WHEN answers1.is_correct = 1 THEN 1 ELSE 0 END) as correct_answers'))
            ->leftJoin('answers1', 'results.auth_user_id', '=', 'answers1.user_id')
            ->groupBy('auth_user_id');

        $challengeUserScores = Result::select('challenge_user_id as user_id', DB::raw('SUM(CASE WHEN answers1.is_correct = 1 THEN 1 ELSE 0 END) as correct_answers'))
            ->leftJoin('answers1', 'results.challenge_user_id', '=', 'answers1.user_id')
            ->groupBy('challenge_user_id');

        // Combine both queries
        $combinedScores = $authUserScores->unionAll($challengeUserScores)
            ->orderBy('correct_answers', 'desc')
            ->limit(1)
            ->get();

        // Insert the top scorer into the top_scorer table
        if ($combinedScores->isNotEmpty()) {
            $topScorer = $combinedScores->first();

            DB::table('top_scorer')->updateOrInsert(
                ['user_id' => $topScorer->user_id],
                ['correct_answers' => $topScorer->correct_answers]
            );
        }

        // Fetch the top scorer from the top_scorer table
        $topScorer = DB::table('top_scorer')->orderBy('correct_answers', 'desc')->first();

        return response()->json($topScorer);

        
    }
 
    public function getUserCorrectAnswers(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
    
        $userId = $request->user_id;  // Get user_id from the request
    
        $correctAnswers = Answer1::where('user_id', $userId)
                                 ->where('is_correct', 1)
                                 ->get();
    
        return response()->json($correctAnswers);  
    }
    
    public function getUserCorrectAnswersCount(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
    
        $userId = $request->user_id;  // Get user_id from the request
    
        $correctAnswersCount = Answer1::where('user_id', $userId)
                                      ->where('is_correct', 1)
                                      ->count();
    
        return response()->json(['correct_answers_count' => $correctAnswersCount]);
    }
}
