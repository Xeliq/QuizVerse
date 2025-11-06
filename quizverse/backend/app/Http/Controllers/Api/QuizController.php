<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Foreach_;

class QuizController extends Controller
{
    // lista quizów użytkownika
    public function index()
    {
        $quizzes = Quiz::where('user_id', Auth::id())->with('category')->get();
        return response()->json($quizzes);
    }

    // lista wszystkich quizów
    public function showAll()
    {
        $quizzes = Quiz::with('category')->get();
        return response()->json($quizzes);
    }

    // lista wszystkich quizów by cattegory
    public function cattegoryShowAll($id)
    {
        $quizzes = Quiz::where('quizzes.category_id', $id)->get();
        return response()->json($quizzes);
    }

    // tworzenie quizu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.points' => 'required|numeric',
            'questions.*.image' => 'nullable|image|max:2048',
            'questions.*.answers' => 'required|array|min:1',
            'questions.*.answers.*.text' => 'required|string',
            'questions.*.answers.*.is_correct' => 'required',
            'questions.*.answers.*.image' => 'nullable|image|max:2048',
        ]);

        $quiz = Quiz::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'user_id' => Auth::id(),
        ]);
        foreach($validated['questions'] as $question) {
            $imagePath = null;
            if (!empty($question['image']) && $question['image'] instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $question['image']->store('question_images', 'public');
            }
            // Dodaj pytania
            $saved_question = $quiz->questions()->create([
                'text' => $question['text'],
                'points' => $question['points'],
                'image_path' => $imagePath
            ]);

            $answersData = [];
            // Dodaj odpowiedzi do każdego pytania
            foreach ($question['answers'] as $answer) {
                $answerImage = null;
                if (isset($answer['image']) && $answer['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $answerImage = $answer['image']->store('answer_images', 'public');
                }

                $isCorrect = filter_var($answer['is_correct'], FILTER_VALIDATE_BOOLEAN);
                $answersData[] = [
                    'text' => $answer['text'],
                    'is_correct' => $isCorrect,
                    'image_path' => $answerImage,
                ];
            }
            $saved_question->answers()->createMany($answersData);
        }
        return response()->json([
            'message' => 'Quiz created successfully',
            'quiz' => $quiz
        ], 201);
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.points' => 'required|numeric',
            'questions.*.image' => 'nullable|image|max:2048',
            'questions.*.answers' => 'required|array|min:1',
            'questions.*.answers.*.text' => 'required|string',
            'questions.*.answers.*.is_correct' => 'required',
            'questions.*.answers.*.image' => 'nullable|image|max:2048',
        ]);

        // Aktualizacja quizu
        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
        ]);

        // Usunięcie starych pytań i odpowiedzi
        foreach ($quiz->questions as $oldQuestion) {
            $oldQuestion->answers()->delete();
        }
        $quiz->questions()->delete();

        // Dodanie nowych pytań i odpowiedzi
        foreach ($validated['questions'] as $question) {
            $imagePath = null;
            if (!empty($question['image']) && $question['image'] instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $question['image']->store('question_images', 'public');
            }

            $saved_question = $quiz->questions()->create([
                'text' => $question['text'],
                'points' => $question['points'],
                'image_path' => $imagePath,
            ]);

            $answersData = [];
            foreach ($question['answers'] as $answer) {
                $answerImage = null;
                if (isset($answer['image']) && $answer['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $answerImage = $answer['image']->store('answer_images', 'public');
                }

                $isCorrect = filter_var($answer['is_correct'], FILTER_VALIDATE_BOOLEAN);
                $answersData[] = [
                    'text' => $answer['text'],
                    'is_correct' => $isCorrect,
                    'image_path' => $answerImage,
                ];
            }

            $saved_question->answers()->createMany($answersData);
        }

        return response()->json([
            'message' => 'Quiz updated successfully',
            'quiz' => $quiz->load('questions.answers')
        ], 200);
    }

    // szczegóły quizu
    public function show($id)
    {
        $quiz = Quiz::with('questions.answers')->findOrFail($id);
        return response()->json($quiz);
    }

    // usunięcie quizu
    public function destroy($id)
    {
        $quiz = Quiz::where('user_id', Auth::id())->findOrFail($id);
        $quiz->delete();
        return response()->json(['message' => 'Quiz deleted successfully']);
    }

    // quiz zapis rezultatu
    public function saveResult(Request $request) {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'score' => 'required|string',
        ]);
        $result = QuizResult::create([
            'quiz_id' => $validated['quiz_id'],
            'score' => $validated['quiz_id'],
            'user_id' => Auth::id(),
        ]);
        return response()->json([
            'message' => 'Result created successfully',
            'result' => $result
        ], 201);
    }
}