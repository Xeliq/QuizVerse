<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="QuizVerse API",
 *     description="API dokumentacji dla aplikacji QuizVerse",
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     securityScheme="bearer"
 * )
 */
class CommentController extends Controller
{
    /**
     * Pobierz wszystkie komentarze do quizu
     *
     * @OA\Get(
     *     path="/api/quizzes/{quizId}/comments",
     *     tags={"Comments"},
     *     summary="Lista wszystkich komentarzy do quizu",
     *     description="Pobiera listę wszystkich komentarzy dla danego quizu, posortowanych od najnowszych",
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="ID quizu",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista komentarzy",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="quiz_id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", nullable=true, example=5),
     *                 @OA\Property(property="user_name", type="string", example="Jan Kowalski"),
     *                 @OA\Property(property="content", type="string", example="Świetny quiz!"),
     *                 @OA\Property(property="rating", type="integer", example=5),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz nie znaleziony"
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
    public function index($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        
        $comments = Comment::where('quiz_id', $quizId)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'quiz_id' => $comment->quiz_id,
                    'user_id' => $comment->user_id,
                    'user_name' => $comment->user ? $comment->user->name : 'Anonimowy użytkownik',
                    'content' => $comment->content,
                    'rating' => $comment->rating,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                ];
            });

        return response()->json($comments);
    }

    /**
     * Pobierz średnią ocenę dla quizu
     *
     * @OA\Get(
     *     path="/api/quizzes/{quizId}/comments/rating",
     *     tags={"Comments"},
     *     summary="Średnia ocena quizu",
     *     description="Pobiera średnią ocenę i liczbę komentarzy dla danego quizu",
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="ID quizu",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Średnia ocena i liczba komentarzy",
     *         @OA\JsonContent(
     *             @OA\Property(property="quiz_id", type="integer", example=1),
     *             @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *             @OA\Property(property="total_comments", type="integer", example=12)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz nie znaleziony"
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
    public function getAverageRating($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        
        $average = Comment::where('quiz_id', $quizId)->avg('rating');
        $count = Comment::where('quiz_id', $quizId)->count();

        return response()->json([
            'quiz_id' => $quizId,
            'average_rating' => $average ? round($average, 2) : 0,
            'total_comments' => $count,
        ]);
    }

    /**
     * Stwórz nowy komentarz
     *
     * @OA\Post(
     *     path="/api/quizzes/{quizId}/comments",
     *     tags={"Comments"},
     *     summary="Utwórz nowy komentarz",
     *     description="Tworzy nowy komentarz do quizu. Uwierzytelniony użytkownik będzie przypisany do komentarza.",
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
     *             required={"content","rating"},
     *             @OA\Property(property="content", type="string", example="Świetny quiz, polecam!", minLength=3, maxLength=1000),
     *             @OA\Property(property="rating", type="integer", example=5, minimum=1, maximum=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Komentarz został utworzony",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="quiz_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", nullable=true, example=5),
     *             @OA\Property(property="user_name", type="string", example="Jan Kowalski"),
     *             @OA\Property(property="content", type="string", example="Świetny quiz, polecam!"),
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Walidacja nie powiodła się"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quiz nie znaleziony"
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
    public function store(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $comment = Comment::create([
            'quiz_id' => $quizId,
            'user_id' => Auth::id(), // W API, jeśli użytkownik niezalogowany, Auth::id() będzie null
            'content' => $validated['content'],
            'rating' => $validated['rating'],
        ]);

        return response()->json([
            'id' => $comment->id,
            'quiz_id' => $comment->quiz_id,
            'user_id' => $comment->user_id,
            'user_name' => Auth::user() ? Auth::user()->name : 'Anonimowy użytkownik',
            'content' => $comment->content,
            'rating' => $comment->rating,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at,
        ], 201);
    }

    /**
     * Pobierz konkretny komentarz
     *
     * @OA\Get(
     *     path="/api/quizzes/{quizId}/comments/{commentId}",
     *     tags={"Comments"},
     *     summary="Pobierz szczegóły komentarza",
     *     description="Pobiera pełne informacje o konkretnym komentarzu",
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="ID quizu",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="commentId",
     *         in="path",
     *         required=true,
     *         description="ID komentarza",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Szczegóły komentarza",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="quiz_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", nullable=true, example=5),
     *             @OA\Property(property="user_name", type="string", example="Jan Kowalski"),
     *             @OA\Property(property="content", type="string", example="Świetny quiz!"),
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Komentarz lub quiz nie znaleziony"
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
    public function show($quizId, $commentId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $comment = Comment::where('quiz_id', $quizId)->findOrFail($commentId);

        return response()->json([
            'id' => $comment->id,
            'quiz_id' => $comment->quiz_id,
            'user_id' => $comment->user_id,
            'user_name' => $comment->user ? $comment->user->name : 'Anonimowy użytkownik',
            'content' => $comment->content,
            'rating' => $comment->rating,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at,
        ]);
    }

    /**
     * Zaktualizuj komentarz (tylko właściciel)
     *
     * @OA\Put(
     *     path="/api/quizzes/{quizId}/comments/{commentId}",
     *     tags={"Comments"},
     *     summary="Zaktualizuj komentarz",
     *     description="Zaktualizuj treść lub ocenę komentarza. Tylko właściciel komentarza może go edytować.",
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="ID quizu",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="commentId",
     *         in="path",
     *         required=true,
     *         description="ID komentarza",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string", example="Zaktualizowana treść", minLength=3, maxLength=1000),
     *             @OA\Property(property="rating", type="integer", example=4, minimum=1, maximum=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Komentarz został zaktualizowany",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="quiz_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", nullable=true, example=5),
     *             @OA\Property(property="user_name", type="string", example="Jan Kowalski"),
     *             @OA\Property(property="content", type="string", example="Zaktualizowana treść"),
     *             @OA\Property(property="rating", type="integer", example=4),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Brak uprawnień do edycji tego komentarza"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Komentarz lub quiz nie znaleziony"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Walidacja nie powiodła się"
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
    public function update(Request $request, $quizId, $commentId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $comment = Comment::where('quiz_id', $quizId)->findOrFail($commentId);

        // Weryfikacja - tylko właściciel lub admin
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Brak uprawnień do edycji tego komentarza'], 403);
        }

        $validated = $request->validate([
            'content' => 'sometimes|string|min:3|max:1000',
            'rating' => 'sometimes|integer|min:1|max:5',
        ]);

        $comment->update($validated);

        return response()->json([
            'id' => $comment->id,
            'quiz_id' => $comment->quiz_id,
            'user_id' => $comment->user_id,
            'user_name' => $comment->user ? $comment->user->name : 'Anonimowy użytkownik',
            'content' => $comment->content,
            'rating' => $comment->rating,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at,
        ]);
    }

    /**
     * Usuń komentarz (tylko właściciel)
     *
     * @OA\Delete(
     *     path="/api/quizzes/{quizId}/comments/{commentId}",
     *     tags={"Comments"},
     *     summary="Usuń komentarz",
     *     description="Usuwa komentarz. Tylko właściciel komentarza może go usunąć.",
     *     @OA\Parameter(
     *         name="quizId",
     *         in="path",
     *         required=true,
     *         description="ID quizu",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="commentId",
     *         in="path",
     *         required=true,
     *         description="ID komentarza",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Komentarz został usunięty",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Komentarz został usunięty")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Brak uprawnień do usunięcia tego komentarza"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Komentarz lub quiz nie znaleziony"
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
    public function destroy($quizId, $commentId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $comment = Comment::where('quiz_id', $quizId)->findOrFail($commentId);

        // Weryfikacja - tylko właściciel lub admin
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Brak uprawnień do usunięcia tego komentarza'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Komentarz został usunięty']);
    }
}