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
    // lista wyników quizów użytkownika
    public function index()
    {
        $results = QuizResult::where('user_id', Auth::id())->with('quiz')->get();
        return response()->json($results);
    }

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