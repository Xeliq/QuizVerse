<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $points = $user->points;

        $rank = User::where('points', '>', $points)->count() + 1;
        $totalUsers = User::count();
        $percentile = $totalUsers > 0 ? round(($rank / $totalUsers) * 100, 2) : 0;

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'rank' => $rank,
            'percentile' => $percentile,
            'points' => $points,
            'completedQuizzes' => $user->completedQuizzes()->select('quizzes.id', 'quizzes.title')->get(),
            'createdQuizzes' => $user->createdQuizzes()->select('quizzes.id', 'quizzes.title')->get(),
        ]);
    }
}
