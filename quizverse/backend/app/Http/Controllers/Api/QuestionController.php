<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Dodaj pytanie do quizu
     *
     * @OA\Post(
     *     path="/quizzes/{quizId}/questions",
     *     tags={"Questions"},
     *     summary="Dodaj pytanie",
     *     description="Dodaje nowe pytanie do quizu (tylko dla właściciela quizu)",
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="ID quizu",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"text"},
     *             @OA\Property(property="text", type="string", example="Jaką jest stolica Polski?"),
     *             @OA\Property(property="points", type="integer", example=10),
     *             @OA\Property(property="image_path", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pytanie zostało dodane",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Question added successfully"),
     *             @OA\Property(
     *                 property="question",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="quiz_id", type="integer"),
     *                 @OA\Property(property="text", type="string"),
     *                 @OA\Property(property="points", type="integer"),
     *                 @OA\Property(property="image_path", type="string", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Brak uprawnień"
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
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
