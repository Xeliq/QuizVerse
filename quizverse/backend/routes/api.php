<?php

use App\Http\Controllers\Api\AppSetingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // quizy
    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::get('/all/quizzes', [QuizController::class, 'showAll']);
    Route::post('/quizzes', [QuizController::class, 'store']);
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update']);
    Route::get('/quizzes/{id}', [QuizController::class, 'show']);
    Route::get('/category/quizzes/{id}', [QuizController::class, 'cattegoryShowAll']);
    Route::post('/quizzes/save-result', [QuizController::class, 'saveResult']);
    Route::delete('/quizzes/{id}', [QuizController::class, 'destroy']);

    // pytania
    Route::post('/quizzes/{quizId}/questions', [QuestionController::class, 'store']);

    // odpowiedzi
    Route::post('/questions/{questionId}/answers', [AnswerController::class, 'store']);
    Route::post('/questions/is-correct/{id}', [AnswerController::class, 'isCorrect']);

    // pobieranie kategorii
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/select', [CategoryController::class, 'selectAllForSelect']);
});