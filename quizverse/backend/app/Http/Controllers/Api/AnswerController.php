<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Question;

class AnswerController extends Controller
{
    public function store(Request $request, $questionId)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'is_correct' => 'required|boolean',
            'image_path' => 'nullable|string',
        ]);

        $question = Question::findOrFail($questionId);

        // sprawdzenie czy użytkownik nie jest właścicielem
        if ($question->quiz->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $answer = Answer::create([
            'question_id' => $questionId,
            'text' => $validated['text'],
            'is_correct' => $validated['is_correct'],
            'image_path' => $validated['image_path'] ?? null,
        ]);

        return response()->json([
            'message' => 'Answer added successfully',
            'answer' => $answer
        ], 201);
    }
}
