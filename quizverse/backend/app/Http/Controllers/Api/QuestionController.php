<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function store(Request $request, $quizId)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'points' => 'nullable|integer|min:1',
            'image_path' => 'nullable|string',
        ]);

        $quiz = Quiz::findOrFail($quizId);

        // sprawdzenie czy uzytkwnik nie jest właściccielem
        if ($quiz->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $question = Question::create([
            'quiz_id' => $quizId,
            'text' => $validated['text'],
            'points' => $validated['points'] ?? 1,
            'image_path' => $validated['image_path'] ?? null,
        ]);

        return response()->json([
            'message' => 'Question added successfully',
            'question' => $question
        ], 201);
    }
}
