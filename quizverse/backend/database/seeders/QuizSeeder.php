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
        |--------------------------------------------------------------------------
        | 1. Quiz: Ogólna wiedza
        |--------------------------------------------------------------------------
        */
        $quiz1 = Quiz::create([
            'user_id' => $users[0]->id,
            'category_id' => $categories['Ogólna wiedza'] ?? null,
            'title' => 'Ogólna wiedza – sprawdź się!',
            'description' => 'Pytania z różnych dziedzin: historia, nauka, geografia i kultura.',
        ]);

        $q1 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'Które z poniższych państw leży w Europie?',
            'points' => 2,
        ]);
        Answer::insert([
            ['question_id' => $q1->id, 'text' => 'Brazylia', 'is_correct' => false],
            ['question_id' => $q1->id, 'text' => 'Hiszpania', 'is_correct' => true],
            ['question_id' => $q1->id, 'text' => 'Egipt', 'is_correct' => false],
            ['question_id' => $q1->id, 'text' => 'Kanada', 'is_correct' => false],
        ]);

        $q2 = Question::create([
            'quiz_id' => $quiz1->id,
            'text' => 'Ile wynosi liczba kontynentów na Ziemi?',
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
            'text' => 'Jakie zwierzę jest symbolem pokoju?',
            'points' => 1,
        ]);
        Answer::insert([
            ['question_id' => $q3->id, 'text' => 'Orzeł', 'is_correct' => false],
            ['question_id' => $q3->id, 'text' => 'Gołąb', 'is_correct' => true],
            ['question_id' => $q3->id, 'text' => 'Lew', 'is_correct' => false],
            ['question_id' => $q3->id, 'text' => 'Tygrys', 'is_correct' => false],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. Geografia
        |--------------------------------------------------------------------------
        */
        $quiz2 = Quiz::create([
            'user_id' => $users[1]->id,
            'category_id' => $categories['Geografia'] ?? null,
            'title' => 'Podróże po świecie',
            'description' => 'Quiz dla miłośników map i geografii!',
        ]);

        $questions = [
            [
                'text' => 'Stolicą Kanady jest:',
                'answers' => ['Toronto', 'Ottawa', 'Vancouver', 'Montreal'],
                'correct' => 1,
            ],
            [
                'text' => 'Największa pustynia na świecie to:',
                'answers' => ['Sahara', 'Gobi', 'Kalahari', 'Atakama'],
                'correct' => 0,
            ],
            [
                'text' => 'Które z poniższych jezior jest największe na świecie?',
                'answers' => ['Jezioro Wiktorii', 'Morze Kaspijskie', 'Bajkał', 'Michigan'],
                'correct' => 1,
            ],
            [
                'text' => 'W jakim kraju znajduje się góra Fuji?',
                'answers' => ['Chiny', 'Japonia', 'Korea Południowa', 'Tajwan'],
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
        |--------------------------------------------------------------------------
        | 3. Historia
        |--------------------------------------------------------------------------
        */
        $quiz3 = Quiz::create([
            'user_id' => $users[2]->id,
            'category_id' => $categories['Historia'] ?? null,
            'title' => 'Historia świata',
            'description' => 'Sprawdź, co pamiętasz z lekcji historii!',
        ]);

        $questions = [
            [
                'text' => 'W którym roku wybuchła II wojna światowa?',
                'answers' => ['1914', '1939', '1945', '1920'],
                'correct' => 1,
            ],
            [
                'text' => 'Kto był pierwszym prezydentem Stanów Zjednoczonych?',
                'answers' => ['Abraham Lincoln', 'George Washington', 'Thomas Jefferson', 'John Adams'],
                'correct' => 1,
            ],
            [
                'text' => 'W którym roku Polska odzyskała niepodległość?',
                'answers' => ['1918', '1939', '1795', '1920'],
                'correct' => 0,
            ],
            [
                'text' => 'Jak nazywał się faraon, który zbudował Wielką Piramidę w Gizie?',
                'answers' => ['Ramzes II', 'Cheops', 'Tutanchamon', 'Echnaton'],
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
        |--------------------------------------------------------------------------
        | 4. Nauka
        |--------------------------------------------------------------------------
        */
        $quiz4 = Quiz::create([
            'user_id' => $users[0]->id,
            'category_id' => $categories['Nauka'] ?? null,
            'title' => 'Świat nauki',
            'description' => 'Fizyka, chemia, biologia – sprawdź się!',
        ]);

        $questions = [
            [
                'text' => 'Który pierwiastek chemiczny ma symbol O?',
                'answers' => ['Wodór', 'Tlen', 'Azot', 'Hel'],
                'correct' => 1,
            ],
            [
                'text' => 'Kto sformułował teorię względności?',
                'answers' => ['Isaac Newton', 'Albert Einstein', 'Galileusz', 'Nikola Tesla'],
                'correct' => 1,
            ],
            [
                'text' => 'Jak nazywa się proces, w którym rośliny produkują tlen?',
                'answers' => ['Fotosynteza', 'Oddychanie', 'Fermentacja', 'Transpiracja'],
                'correct' => 0,
            ],
            [
                'text' => 'Jakie jest przyspieszenie ziemskie (w m/s²)?',
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
        |--------------------------------------------------------------------------
        | 5. Sport
        |--------------------------------------------------------------------------
        */
        $quiz5 = Quiz::create([
            'user_id' => $users[1]->id,
            'category_id' => $categories['Sport'] ?? null,
            'title' => 'Quiz sportowy',
            'description' => 'Piłka nożna, koszykówka i igrzyska olimpijskie!',
        ]);

        $questions = [
            [
                'text' => 'Który kraj wygrał Mistrzostwa Świata w piłce nożnej w 2018 roku?',
                'answers' => ['Niemcy', 'Francja', 'Brazylia', 'Argentyna'],
                'correct' => 1,
            ],
            [
                'text' => 'Ile zawodników gra w drużynie siatkówki na boisku?',
                'answers' => ['5', '6', '7', '9'],
                'correct' => 1,
            ],
            [
                'text' => 'W którym mieście odbyły się Igrzyska Olimpijskie 2008?',
                'answers' => ['Ateny', 'Londyn', 'Pekin', 'Rio de Janeiro'],
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
        |--------------------------------------------------------------------------
        | 6. Muzyka i filmy
        |--------------------------------------------------------------------------
        */
        $quiz6 = Quiz::create([
            'user_id' => $users[2]->id,
            'category_id' => $categories['Muzyka'] ?? null,
            'title' => 'Muzyka i filmy',
            'description' => 'Sprawdź, jak dobrze znasz świat rozrywki!',
        ]);

        $questions = [
            [
                'text' => 'Kto był liderem zespołu Queen?',
                'answers' => ['Elton John', 'Freddie Mercury', 'David Bowie', 'Mick Jagger'],
                'correct' => 1,
            ],
            [
                'text' => 'Który film zdobył Oscara za najlepszy film w 1994 roku?',
                'answers' => ['Pulp Fiction', 'Forrest Gump', 'Skazani na Shawshank', 'Titanic'],
                'correct' => 1,
            ],
            [
                'text' => 'Jak nazywał się fikcyjny świat w serii "Władca Pierścieni"?',
                'answers' => ['Narnia', 'Śródziemie', 'Hogwart', 'Pandora'],
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
        |--------------------------------------------------------------------------
        | 7. Zwierzęta
        |--------------------------------------------------------------------------
        */
        $quiz7 = Quiz::create([
            'user_id' => $users[0]->id,
            'category_id' => $categories['Zwierzęta'] ?? null,
            'title' => 'Świat zwierząt',
            'description' => 'Czy wiesz, które zwierzę potrafi najwięcej?',
        ]);

        $questions = [
            [
                'text' => 'Jakie zwierzę jest największym ssakiem na Ziemi?',
                'answers' => ['Słoń afrykański', 'Płetwal błękitny', 'Orka', 'Nosorożec'],
                'correct' => 1,
            ],
            [
                'text' => 'Który ptak nie potrafi latać?',
                'answers' => ['Pingwin', 'Wróbel', 'Jastrząb', 'Gołąb'],
                'correct' => 0,
            ],
            [
                'text' => 'Jak nazywa się młode konia?',
                'answers' => ['Źrebak', 'Cielak', 'Jagnię', 'Koziołek'],
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
