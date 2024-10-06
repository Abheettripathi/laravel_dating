<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\WinnerService;
use App\Models\Answer;

class AnswerController extends Controller
{
    protected $winnerService;

    /**
     * Create a new controller instance.
     *
     * @param WinnerService $winnerService
     */
    public function __construct(WinnerService $winnerService)
    {
        $this->winnerService = $winnerService;
    }

    /**
     * Determine the winner based on the question ID.
     *
     * @param int $questionId
     * @return Response
     */
    public function determineWinnerByQuestion(int $questionId): Response
    {
        $result = $this->winnerService->determineWinnerByQuestionId($questionId);
        return response()->json($result);
    }

    /**
     * Display a listing of all answers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Answer::all();
    }
}
