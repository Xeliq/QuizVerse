<?php

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="QuizVerse API",
 *         description="API dla aplikacji QuizVerse - platformy do tworzenia i rozwiązywania quizów"
 *     ),
 *     @OA\Server(
 *         url="/api",
 *         description="API Server"
 *     ),
 *     @OA\SecurityScheme(
 *         type="http",
 *         description="Login with email and password to get the authentication token",
 *         name="Token based authentication",
 *         in="header",
 *         scheme="bearer",
 *         bearerFormat="JWT",
 *         securityScheme="bearer"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Comment",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="quiz_id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", nullable=true, example=5),
 *     @OA\Property(property="user_name", type="string", example="Jan Kowalski"),
 *     @OA\Property(property="content", type="string", example="Świetny quiz!"),
 *     @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Response(
 *     response="400",
 *     description="Bad Request",
 *     @OA\JsonContent(
 *         @OA\Property(property="error", type="string")
 *     )
 * )
 *
 * @OA\Response(
 *     response="401",
 *     description="Unauthorized",
 *     @OA\JsonContent(
 *         @OA\Property(property="message", type="string", example="Unauthenticated")
 *     )
 * )
 *
 * @OA\Response(
 *     response="403",
 *     description="Forbidden",
 *     @OA\JsonContent(
 *         @OA\Property(property="message", type="string", example="Forbidden")
 *     )
 * )
 *
 * @OA\Response(
 *     response="404",
 *     description="Not Found",
 *     @OA\JsonContent(
 *         @OA\Property(property="message", type="string", example="Not found")
 *     )
 * )
 *
 * @OA\Response(
 *     response="422",
 *     description="Unprocessable Entity",
 *     @OA\JsonContent(
 *         @OA\Property(property="message", type="string", example="The given data was invalid"),
 *         @OA\Property(
 *             property="errors",
 *             type="object",
 *             example={"content": {"The content field is required."}}
 *         )
 *     )
 * )
 */
