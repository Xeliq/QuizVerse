<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Foreach_;

class QuizResultsController extends Controller
{
    /**
     * Lista moich wyników
     *
     * @OA\Get(
     *     path="/quiz-results",
     *     tags={"Quiz Results"},
     *     summary="Moje wyniki",
     *     description="Pobiera listę wyników quizów zalogowanego użytkownika",
     *     @OA\Response(
     *         response=200,
     *         description="Lista wyników",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="quiz_id", type="integer", example=1),
     *                 @OA\Property(property="points", type="integer", example=85),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
    public function index()
    {
        $results = QuizResult::where('user_id', Auth::id())->with('quiz')->get();
        return response()->json($results);
    }

    /**
     * Szczegóły wyniku
     *
     * @OA\Get(
     *     path="/quiz-results/{id}",
     *     tags={"Quiz Results"},
     *     summary="Szczegóły wyniku",
     *     description="Pobiera szczegóły wyniku quizu z pytaniami",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID wyniku",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Szczegóły wyniku",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="quiz_id", type="integer", example=1),
     *             @OA\Property(property="points", type="integer", example=85),
     *             @OA\Property(property="total", type="integer", example=100),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wynik nie znaleziony"
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
    public function show($id)
    {
        $result = QuizResult::with('quiz.questions')
                            ->where('id', $id)
                            ->where('user_id', Auth::id())
                            ->first();

        if (!$result) {
            return response()->json(['message' => 'Result not found'], 404);
        }

        // obliczamy total punktów quizu
        $totalPoints = $result->quiz->questions->sum('points');

        // dodajemy do odpowiedzi
        $resultArray = $result->toArray();
        $resultArray['total'] = $totalPoints;

        return response()->json($resultArray);
    }




}