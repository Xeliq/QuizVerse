<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Foreach_;
use App\Models\Answer;
use App\Models\Category;
use App\Models\User;
use App\Models\Question;


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
        // Pobieramy wszystkie quizy z kategorią i liczba wyników (plays)
        $quizzes = Quiz::with('category')
            ->withCount('results') // <- tutaj dostajemy licznik wyników
            ->get();

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
        $quiz = Quiz::with(['category', 'user', 'questions.answers'])->findOrFail($id);
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
            'score' => 'required|integer|min:0',
        ]);
        $result = QuizResult::create([
            'quiz_id' => $validated['quiz_id'],
            'score' => $validated['score'],
            'user_id' => Auth::id(),
        ]);
        $user = Auth::user();
        $user->points = $user->quizResults()->sum('score');
        $user->save();
        return response()->json([
            'message' => 'Result created successfully',
            'result' => $result
        ], 201);
    }

    public function getRankingsData()
    {
        // Liczba quizów w każdej kategorii (do wykresu słupkowego)
        $quizzesPerCategory = Category::withCount('quizzes')
            ->get()
            ->map(fn($cat) => [
                'category' => $cat->name,
                'count' => $cat->quizzes_count
            ]);

        // Średni wynik w quizach (do wykresu liniowego)
        $avgScores = QuizResult::selectRaw('quiz_id, AVG(score) as avg_score')
            ->groupBy('quiz_id')
            ->with('quiz')
            ->get()
            ->map(fn($res) => [
                'quiz' => $res->quiz->title,
                'avg_score' => round($res->avg_score, 2)
            ]);

        // Top 5 użytkowników wg punktów
        $topUsers = QuizResult::selectRaw('user_id, SUM(score) as total_score')
            ->groupBy('user_id')
            ->orderByDesc('total_score')
            ->with('user')
            ->limit(5)
            ->get()
            ->map(fn($res) => [
                'user' => $res->user->name,
                'score' => $res->total_score
            ]);

        // Liczba pytań w quizach
        $questionsPerQuiz = Quiz::withCount('questions')
            ->get()
            ->map(fn($quiz) => [
                'quiz' => $quiz->title,
                'questions' => $quiz->questions_count
            ]);

        // Liczba poprawnych odpowiedzi vs błędnych
        $answersStats = [
            'correct' => Answer::where('is_correct', true)->count(),
            'incorrect' => Answer::where('is_correct', false)->count(),
        ];

        $result = [
            'quizzesPerCategory' => $quizzesPerCategory,
            'avgScores' => $avgScores,
            'topUsers' => $topUsers,
            'questionsPerQuiz' => $questionsPerQuiz,
            'answersStats' => $answersStats,
        ];

        return response()->json([
            'message' => 'Result created successfully',
            'result' => $result
        ], 201);
    }
}