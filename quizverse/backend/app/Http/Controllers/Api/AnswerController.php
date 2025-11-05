<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    /*czy odpowiedzi jest poprawna*/
    public function isCorrect($id)
    {
        $answer = Answer::find($id);

        if (!$answer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Answer not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'answer_id' => $answer->id,
            'question_id' => $answer->question_id,
            'is_correct' => (bool) $answer->is_correct
        ]);
    }

    public function store(Request $request, $questionId)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'is_correct' => 'required|boolean',
            'image_path' => 'nullable|string',
        ]);

        $question = Question::findOrFail($questionId);

        // sprawdzenie czy użytkownik nie jest właścicielem
        if ($question->quiz->user_id !== Auth::id()) {
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
