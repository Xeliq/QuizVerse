<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    /**
     * Sprawdź czy odpowiedź jest poprawna
     *
     * @OA\Post(
     *     path="/questions/is-correct/{id}",
     *     tags={"Answers"},
     *     summary="Sprawdź odpowiedź",
     *     description="Sprawdza czy odpowiedź jest poprawna",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID odpowiedzi",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status odpowiedzi",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="answer_id", type="integer", example=1),
     *             @OA\Property(property="question_id", type="integer", example=1),
     *             @OA\Property(property="is_correct", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Odpowiedź nie znaleziona"
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
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

    /**
     * Dodaj odpowiedź do pytania
     *
     * @OA\Post(
     *     path="/questions/{questionId}/answers",
     *     tags={"Answers"},
     *     summary="Dodaj odpowiedź",
     *     description="Dodaje nową odpowiedź do pytania (tylko dla właściciela quizu)",
     *     @OA\Parameter(
     *         name="questionId",
     *         in="path",
     *         required=true,
     *         description="ID pytania",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"text","is_correct"},
     *             @OA\Property(property="text", type="string", example="Warszawa"),
     *             @OA\Property(property="is_correct", type="boolean", example=true),
     *             @OA\Property(property="image_path", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Odpowiedź została dodana",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Answer added successfully"),
     *             @OA\Property(
     *                 property="answer",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="question_id", type="integer"),
     *                 @OA\Property(property="text", type="string"),
     *                 @OA\Property(property="is_correct", type="boolean"),
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
