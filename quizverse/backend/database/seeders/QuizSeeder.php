<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\Category;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::pluck('id', 'name');

        /*
        |----------------------------------------------------------------------
        | 1. General Knowledge
        |----------------------------------------------------------------------
        */
        $quiz1 = Quiz::create([
            'user_id' => $users[0]->id,
            'category_id' => $categories['General Knowledge'] ?? null,
            'title' => 'General Knowledge - Test Yourself!',
            'description' => 'Questions from various fields: history, science, geography, and culture.',
        ]);

        $q1 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'Which of the following countries is in Europe?',
            'points' => 2,
        ]);
        Answer::insert([
            ['question_id' => $q1->id, 'text' => 'Brazil', 'is_correct' => false],
            ['question_id' => $q1->id, 'text' => 'Spain', 'is_correct' => true],
            ['question_id' => $q1->id, 'text' => 'Egypt', 'is_correct' => false],
            ['question_id' => $q1->id, 'text' => 'Canada', 'is_correct' => false],
        ]);

        $q2 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'How many continents are there on Earth?',
            'points' => 1,
        ]);
        Answer::insert([
            ['question_id' => $q2->id, 'text' => '5', 'is_correct' => false],
            ['question_id' => $q2->id, 'text' => '6', 'is_correct' => false],
            ['question_id' => $q2->id, 'text' => '7', 'is_correct' => true],
            ['question_id' => $q2->id, 'text' => '8', 'is_correct' => false],
        ]);

        $q3 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'Which animal is a symbol of peace?',
            'points' => 1,
        ]);
        Answer::insert([
            ['question_id' => $q3->id, 'text' => 'Eagle', 'is_correct' => false],
            ['question_id' => $q3->id, 'text' => 'Dove', 'is_correct' => true],
            ['question_id' => $q3->id, 'text' => 'Lion', 'is_correct' => false],
            ['question_id' => $q3->id, 'text' => 'Tiger', 'is_correct' => false],
        ]);

        /*
        |----------------------------------------------------------------------
        | 2. Geography
        |----------------------------------------------------------------------
        */
        $quiz2 = Quiz::create([
            'user_id' => $users[1]->id,
            'category_id' => $categories['Geography'] ?? null,
            'title' => 'Travel Around the World',
            'description' => 'A quiz for map and geography lovers!',
        ]);

        $questions = [
            [
                'text' => 'The capital of Canada is:',
                'answers' => ['Toronto', 'Ottawa', 'Vancouver', 'Montreal'],
                'correct' => 1,
            ],
            [
                'text' => 'The largest desert in the world is:',
                'answers' => ['Sahara', 'Gobi', 'Kalahari', 'Atacama'],
                'correct' => 0,
            ],
            [
                'text' => 'Which of the following lakes is the largest in the world?',
                'answers' => ['Lake Victoria', 'Caspian Sea', 'Baikal', 'Michigan'],
                'correct' => 1,
            ],
            [
                'text' => 'In which country is Mount Fuji located?',
                'answers' => ['China', 'Japan', 'South Korea', 'Taiwan'],
                'correct' => 1,
            ],
        ];

        foreach ($questions as $q) {
            $question = Question::create([
                'quiz_id' => $quiz2->id,
                'text' => $q['text'],
                'points' => 2,
            ]);
            foreach ($q['answers'] as $i => $a) {
                Answer::create([
                    'question_id' => $question->id,
                    'text' => $a,
                    'is_correct' => $i === $q['correct'],
                ]);
            }
        }

        /*
        |----------------------------------------------------------------------
        | 3. History
        |----------------------------------------------------------------------
        */
        $quiz3 = Quiz::create([
            'user_id' => $users[2]->id,
            'category_id' => $categories['History'] ?? null,
            'title' => 'World History',
            'description' => 'Test what you remember from history lessons!',
        ]);

        $questions = [
            [
                'text' => 'In which year did World War II begin?',
                'answers' => ['1914', '1939', '1945', '1920'],
                'correct' => 1,
            ],
            [
                'text' => 'Who was the first President of the United States?',
                'answers' => ['Abraham Lincoln', 'George Washington', 'Thomas Jefferson', 'John Adams'],
                'correct' => 1,
            ],
            [
                'text' => 'In which year did Poland regain independence?',
                'answers' => ['1918', '1939', '1795', '1920'],
                'correct' => 0,
            ],
            [
                'text' => 'What was the name of the pharaoh who built the Great Pyramid of Giza?',
                'answers' => ['Ramesses II', 'Khufu', 'Tutankhamun', 'Akhenaten'],
                'correct' => 1,
            ],
        ];

        foreach ($questions as $q) {
            $question = Question::create([
                'quiz_id' => $quiz3->id,
                'text' => $q['text'],
                'points' => 2,
            ]);
            foreach ($q['answers'] as $i => $a) {
                Answer::create([
                    'question_id' => $question->id,
                    'text' => $a,
                    'is_correct' => $i === $q['correct'],
                ]);
            }
        }

        /*
        |----------------------------------------------------------------------
        | 4. Science
        |----------------------------------------------------------------------
        */
        $quiz4 = Quiz::create([
            'user_id' => $users[0]->id,
            'category_id' => $categories['Science'] ?? null,
            'title' => 'World of Science',
            'description' => 'Physics, chemistry, biology – test yourself!',
        ]);

        $questions = [
            [
                'text' => 'Which chemical element has the symbol O?',
                'answers' => ['Hydrogen', 'Oxygen', 'Nitrogen', 'Helium'],
                'correct' => 1,
            ],
            [
                'text' => 'Who formulated the theory of relativity?',
                'answers' => ['Isaac Newton', 'Albert Einstein', 'Galileo', 'Nikola Tesla'],
                'correct' => 1,
            ],
            [
                'text' => 'What is the process called in which plants produce oxygen?',
                'answers' => ['Photosynthesis', 'Respiration', 'Fermentation', 'Transpiration'],
                'correct' => 0,
            ],
            [
                'text' => 'What is the acceleration due to gravity on Earth (in m/s²)?',
                'answers' => ['9.81', '10', '8.9', '11.2'],
                'correct' => 0,
            ],
        ];

        foreach ($questions as $q) {
            $question = Question::create([
                'quiz_id' => $quiz4->id,
                'text' => $q['text'],
                'points' => 2,
            ]);
            foreach ($q['answers'] as $i => $a) {
                Answer::create([
                    'question_id' => $question->id,
                    'text' => $a,
                    'is_correct' => $i === $q['correct'],
                ]);
            }
        }

        /*
        |----------------------------------------------------------------------
        | 5. Sports
        |----------------------------------------------------------------------
        */
        $quiz5 = Quiz::create([
            'user_id' => $users[1]->id,
            'category_id' => $categories['Sports'] ?? null,
            'title' => 'Sports Quiz',
            'description' => 'Football, basketball, and the Olympic Games!',
        ]);

        $questions = [
            [
                'text' => 'Which country won the FIFA World Cup in 2018?',
                'answers' => ['Germany', 'France', 'Brazil', 'Argentina'],
                'correct' => 1,
            ],
            [
                'text' => 'How many players are on a volleyball team on the court?',
                'answers' => ['5', '6', '7', '9'],
                'correct' => 1,
            ],
            [
                'text' => 'In which city were the 2008 Olympic Games held?',
                'answers' => ['Athens', 'London', 'Beijing', 'Rio de Janeiro'],
                'correct' => 2,
            ],
        ];

        foreach ($questions as $q) {
            $question = Question::create([
                'quiz_id' => $quiz5->id,
                'text' => $q['text'],
                'points' => 2,
            ]);
            foreach ($q['answers'] as $i => $a) {
                Answer::create([
                    'question_id' => $question->id,
                    'text' => $a,
                    'is_correct' => $i === $q['correct'],
                ]);
            }
        }

        /*
        |----------------------------------------------------------------------
        | 6. Music & Movies
        |----------------------------------------------------------------------
        */
        $quiz6 = Quiz::create([
            'user_id' => $users[2]->id,
            'category_id' => $categories['Music'] ?? null,
            'title' => 'Music & Movies',
            'description' => 'Test how well you know the entertainment world!',
        ]);

        $questions = [
            [
                'text' => 'Who was the lead singer of Queen?',
                'answers' => ['Elton John', 'Freddie Mercury', 'David Bowie', 'Mick Jagger'],
                'correct' => 1,
            ],
            [
                'text' => 'Which movie won the Oscar for Best Picture in 1994?',
                'answers' => ['Pulp Fiction', 'Forrest Gump', 'The Shawshank Redemption', 'Titanic'],
                'correct' => 1,
            ],
            [
                'text' => 'What was the fictional world in "The Lord of the Rings" series?',
                'answers' => ['Narnia', 'Middle-earth', 'Hogwarts', 'Pandora'],
                'correct' => 1,
            ],
        ];

        foreach ($questions as $q) {
            $question = Question::create([
                'quiz_id' => $quiz6->id,
                'text' => $q['text'],
                'points' => 2,
            ]);
            foreach ($q['answers'] as $i => $a) {
                Answer::create([
                    'question_id' => $question->id,
                    'text' => $a,
                    'is_correct' => $i === $q['correct'],
                ]);
            }
        }

        /*
        |----------------------------------------------------------------------
        | 7. Animals
        |----------------------------------------------------------------------
        */
        $quiz7 = Quiz::create([
            'user_id' => $users[0]->id,
            'category_id' => $categories['Animals'] ?? null,
            'title' => 'Animal World',
            'description' => 'Do you know which animal can do the most?',
        ]);

        $questions = [
            [
                'text' => 'Which animal is the largest mammal on Earth?',
                'answers' => ['African Elephant', 'Blue Whale', 'Orca', 'Rhinoceros'],
                'correct' => 1,
            ],
            [
                'text' => 'Which bird cannot fly?',
                'answers' => ['Penguin', 'Sparrow', 'Hawk', 'Pigeon'],
                'correct' => 0,
            ],
            [
                'text' => 'What is a baby horse called?',
                'answers' => ['Foal', 'Calf', 'Lamb', 'Kid'],
                'correct' => 0,
            ],
        ];

        foreach ($questions as $q) {
            $question = Question::create([
                'quiz_id' => $quiz7->id,
                'text' => $q['text'],
                'points' => 1,
            ]);
            foreach ($q['answers'] as $i => $a) {
                Answer::create([
                    'question_id' => $question->id,
                    'text' => $a,
                    'is_correct' => $i === $q['correct'],
                ]);
            }
        }
    }
}
