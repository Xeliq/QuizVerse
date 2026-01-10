<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Get(
 *     path="/all/quizzes",
 *     tags={"Quizzes"},
 *     summary="Lista wszystkich quizów",
 *     description="Pobiera listę wszystkich quizów z kategorią i liczbą rozwiązań",
 *     @OA\Response(
 *         response=200,
 *         description="Lista quizów",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="category_id", type="integer", nullable=true, example=1),
 *                 @OA\Property(property="title", type="string", example="Historia Polski"),
 *                 @OA\Property(property="description", type="string", nullable=true),
 *                 @OA\Property(property="results_count", type="integer", example=5),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time")
 *             )
 *         )
 *     ),
 *     security={{"bearer": {}}}
 * )
 * 
 * @OA\Post(
 *     path="/quizzes",
 *     tags={"Quizzes"},
 *     summary="Utwórz nowy quiz",
 *     description="Tworzy nowy quiz z pytaniami i odpowiedziami",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","questions"},
 *             @OA\Property(property="title", type="string", example="Historia Polski"),
 *             @OA\Property(property="description", type="string", nullable=true),
 *             @OA\Property(property="category_id", type="integer", nullable=true, example=1),
 *             @OA\Property(
 *                 property="questions",
 *                 type="array",
 *                 minItems=1,
 *                 @OA\Items(
 *                     @OA\Property(property="text", type="string", example="Kiedy został założony polski staat?"),
 *                     @OA\Property(property="points", type="integer", example=10),
 *                     @OA\Property(
 *                         property="answers",
 *                         type="array",
 *                         minItems=1,
 *                         @OA\Items(
 *                             @OA\Property(property="text", type="string", example="966"),
 *                             @OA\Property(property="is_correct", type="boolean", example=true)
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Quiz został utworzony",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Quiz created successfully"),
 *             @OA\Property(
 *                 property="quiz",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string"),
 *                 @OA\Property(property="description", type="string", nullable=true),
 *                 @OA\Property(property="category_id", type="integer", nullable=true)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Błąd walidacji"
 *     ),
 *     security={{"bearer": {}}}
 * )
 * 
 * @OA\Put(
 *     path="/quizzes/{quiz}",
 *     tags={"Quizzes"},
 *     summary="Zaktualizuj quiz",
 *     description="Aktualizuje dane quizu",
 *     @OA\Parameter(
 *         name="quiz",
 *         in="path",
 *         required=true,
 *         description="ID quizu",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="category_id", type="integer", nullable=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Quiz zaktualizowany"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Brak uprawnień"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Quiz nie znaleziony"
 *     ),
 *     security={{"bearer": {}}}
 * )
 * 
 * @OA\Get(
 *     path="/quizzes/{id}",
 *     tags={"Quizzes"},
 *     summary="Pobierz quiz z pytaniami",
 *     description="Pobiera szczegóły quizu wraz z pytaniami i odpowiedziami",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID quizu",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Szczegóły quizu"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Quiz nie znaleziony"
 *     ),
 *     security={{"bearer": {}}}
 * )
 * 
 * @OA\Get(
 *     path="/category/quizzes/{id}",
 *     tags={"Quizzes"},
 *     summary="Quizy z kategorii",
 *     description="Pobiera wszystkie quizy z wybranej kategorii",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID kategorii",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista quizów z kategorii"
 *     ),
 *     security={{"bearer": {}}}
 * )
 * 
 * @OA\Post(
 *     path="/quizzes/save-result",
 *     tags={"Quizzes"},
 *     summary="Zapisz wynik quizu",
 *     description="Zapisuje wynik rozwiązanego quizu",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"quiz_id","points"},
 *             @OA\Property(property="quiz_id", type="integer", example=1),
 *             @OA\Property(property="points", type="integer", example=85)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Wynik został zapisany"
 *     ),
 *     security={{"bearer": {}}}
 * )
 * 
 * @OA\Delete(
 *     path="/quizzes/{id}",
 *     tags={"Quizzes"},
 *     summary="Usuń quiz",
 *     description="Usuwa quiz (tylko właściciel)",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID quizu",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Quiz usunięty"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Brak uprawnień"
 *     ),
 *     security={{"bearer": {}}}
 * )
 * 
 * @OA\Get(
 *     path="/get-ranking-data",
 *     tags={"Quizzes"},
 *     summary="Dane rankingowe",
 *     description="Pobiera dane do rankingu użytkowników",
 *     @OA\Response(
 *         response=200,
 *         description="Dane rankingowe"
 *     ),
 *     security={{"bearer": {}}}
 * )
 */
class ApiDocumentation {}
