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

}