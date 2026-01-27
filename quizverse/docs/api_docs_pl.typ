#set page(
  paper: "a4",
  margin: (top: 20mm, bottom: 20mm, left: 20mm, right: 20mm),
)

#set text(
  font: "Liberation Sans",
  size: 10pt,
  lang: "pl",
)

#show raw.where(block: true): it => block(
  fill: luma(245),
  inset: 8pt,
  radius: 3pt,
  stroke: luma(220),
  it
)

#show raw.where(block: false): it => box(
  fill: luma(245),
  inset: 2pt,
  radius: 3pt,
  stroke: luma(220),
  it
)

= QuizVerse API - Dokumentacja API
Wersja: *1.0.0*  
Specyfikacja: *OAS3*  
OpenAPI: `/storage/api-docs/api-docs.json`

== Wprowadzenie
Niniejszy dokument opisuje dostępne endpointy QuizVerse API na podstawie danych ze Swagger UI.
Zawiera ścieżki, opisy, parametry, przykładowe body oraz przykładowe odpowiedzi.

== Konwencje
- Wszystkie request/response są w formacie JSON (o ile nie zaznaczono inaczej).
- Identyfikatory w ścieżkach (`{id}`, `{quizId}`, `{commentId}`, `{questionId}`) są typu *integer*.
- Kody odpowiedzi:
  - `200` OK
  - `201` Created
  - `403` Forbidden
  - `404` Not Found
  - `422` Validation error

#pagebreak()

= Endpoints

== Answers

#line(length: 100%, stroke: 1pt + luma(200))

=== POST `/questions/is-correct/{id}` - Sprawdź odpowiedź
Sprawdza czy odpowiedź jest poprawna.

*Parametry ścieżki:*
- `id` (integer) - ID odpowiedzi

*Odpowiedzi:*
- `200` - Status odpowiedzi
- `404` - Odpowiedź nie znaleziona

*Przykład odpowiedzi (200):*
```json
{
  "status": "success",
  "answer_id": 1,
  "question_id": 1,
  "is_correct": true
}
```

#line(length: 100%, stroke: 1pt + luma(200))

=== POST `/questions/{questionId}/answers` - Dodaj odpowiedź
Dodaje nową odpowiedź do pytania (*tylko dla właściciela quizu*).

*Parametry ścieżki:*
- `questionId` (integer) - ID pytania

*Body (JSON):*
```json
{
  "text": "Warszawa",
  "is_correct": true,
  "image_path": "string"
}
```

*Odpowiedzi:*
- `201` - Odpowiedź została dodana
- `403` - Brak uprawnień

*Przykład odpowiedzi (201):*
```json
{
  "message": "Answer added successfully",
  "answer": {
    "id": 1,
    "question_id": 0,
    "text": "string",
    "is_correct": true,
    "image_path": "string"
  }
}
```

#pagebreak()

== Quizzes

#line(length: 100%, stroke: 1pt + luma(200))

=== GET `/all/quizzes` - Lista wszystkich quizów
Pobiera listę wszystkich quizów z kategorią i liczbą rozwiązań.

*Parametry:* brak

*Odpowiedzi:*
- `200` - Lista quizów

*Przykład odpowiedzi (200):*
```json
[
  {
    "id": 1,
    "user_id": 1,
    "category_id": 1,
    "title": "Historia Polski",
    "description": "string",
    "results_count": 5,
    "created_at": "2026-01-27T17:22:57.076Z",
    "updated_at": "2026-01-27T17:22:57.076Z"
  }
]
```

#line(length: 100%, stroke: 1pt + luma(200))

=== GET `/quizzes` - Lista moich quizów
Pobiera listę quizów stworzonych przez zalogowanego użytkownika.

*Parametry:* brak

*Odpowiedzi:*
- `200` - Lista quizów

*Przykład odpowiedzi (200):*
```json
[
  {
    "id": 1,
    "user_id": 1,
    "category_id": 1,
    "title": "Historia Polski",
    "description": "string",
    "created_at": "2026-01-27T17:22:57.078Z",
    "updated_at": "2026-01-27T17:22:57.078Z"
  }
]
```

#line(length: 100%, stroke: 1pt + luma(200))
=== POST `/quizzes` - Utwórz nowy quiz
Tworzy nowy quiz z pytaniami i odpowiedziami.

*Parametry:* brak

*Body (JSON):*
#pagebreak()
```json
{
  "title": "Historia Polski",
  "description": "string",
  "category_id": 1,
  "questions": [
    {
      "text": "Kiedy został założony polski staat?",
      "points": 10,
      "answers": [
        {
          "text": "966",
          "is_correct": true
        }
      ]
    }
  ]
}
```

*Odpowiedzi:*
- `201` - Quiz został utworzony
- `422` - Błąd walidacji

*Przykład odpowiedzi (201):*
```json
{
  "message": "Quiz created successfully",
  "quiz": {
    "id": 1,
    "user_id": 1,
    "title": "string",
    "description": "string",
    "category_id": 0
  }
}
```

#line(length: 100%, stroke: 1pt + luma(200))

=== PUT `/quizzes/{quiz}` - Zaktualizuj quiz
Aktualizuje dane quizu.

*Parametry ścieżki:*
- `quiz` (integer) - ID quizu

*Body (JSON):*
```json
{
  "title": "string",
  "description": "string",
  "category_id": 0
}
```

*Odpowiedzi:*
- `200` - Quiz zaktualizowany
- `403` - Brak uprawnień
- `404` - Quiz nie znaleziony

#line(length: 100%, stroke: 1pt + luma(200))

#pagebreak()
=== GET `/quizzes/{id}` - Pobierz quiz z pytaniami
Pobiera szczegóły quizu wraz z pytaniami i odpowiedziami.

*Parametry ścieżki:*
- `id` (integer) - ID quizu

*Odpowiedzi:*
- `200` - Szczegóły quizu
- `404` - Quiz nie znaleziony

#line(length: 100%, stroke: 1pt + luma(200))

=== DELETE `/quizzes/{id}` - Usuń quiz
Usuwa quiz (*tylko właściciel*).

*Parametry ścieżki:*
- `id` (integer) - ID quizu

*Odpowiedzi:*
- `200` - Quiz usunięty
- `403` - Brak uprawnień
- `404` - Quiz nie znaleziony

#line(length: 100%, stroke: 1pt + luma(200))

=== GET `/category/quizzes/{id}` - Quizy z kategorii
Pobiera wszystkie quizy z wybranej kategorii.

*Parametry ścieżki:*
- `id` (integer) - ID kategorii

*Odpowiedzi:*
- `200` - Lista quizów z kategorii

#line(length: 100%, stroke: 1pt + luma(200))

=== POST `/quizzes/save-result` - Zapisz wynik quizu
Zapisuje wynik rozwiązanego quizu.

*Body (JSON):*
```json
{
  "quiz_id": 1,
  "points": 85
}
```

*Odpowiedzi:*
- `201` - Wynik został zapisany

#line(length: 100%, stroke: 1pt + luma(200))

=== GET `/get-ranking-data` - Dane rankingowe
Pobiera dane do rankingu użytkowników.

*Odpowiedzi:*
- `200` - Dane rankingowe

#pagebreak()

== Categories

#line(length: 100%, stroke: 1pt + luma(200))

=== GET `/categories` - Lista kategorii
Pobiera listę wszystkich kategorii.

*Odpowiedzi:*
- `200` - Lista kategorii

*Przykład odpowiedzi (200):*
```json
{
  "status": "success",
  "categories": [
    {
      "id": 1,
      "name": "Historia",
      "created_at": "2026-01-27T17:22:57.089Z",
      "updated_at": "2026-01-27T17:22:57.089Z"
    }
  ]
}
```

#line(length: 100%, stroke: 1pt + luma(200))

=== GET `/categories/select` - Kategorie dla selecta
Pobiera kategorie w formacie `id => name` dla selectów.

*Odpowiedzi:*
- `200` - Kategorie

*Przykład odpowiedzi (200):*
```json
{
  "status": "success",
  "categories": {
    "1": "Historia",
    "2": "Geografia"
  }
}
```

#pagebreak()

== Comments

#line(length: 100%, stroke: 1pt + luma(200))

=== GET `/api/quizzes/{quizId}/comments` - Lista komentarzy do quizu
Pobiera listę komentarzy dla danego quizu (od najnowszych).

*Parametry ścieżki:*
- `quizId` (integer) - ID quizu

*Odpowiedzi:*
- `200` - Lista komentarzy
- `404` - Quiz nie znaleziony

*Przykład odpowiedzi (200):*
```json
[
  {
    "id": 1,
    "quiz_id": 1,
    "user_id": 5,
    "user_name": "Jan Kowalski",
    "content": "Świetny quiz!",
    "rating": 5,
    "created_at": "2026-01-27T17:22:57.093Z",
    "updated_at": "2026-01-27T17:22:57.093Z"
  }
]
```

#line(length: 100%, stroke: 1pt + luma(200))

=== POST `/api/quizzes/{quizId}/comments` - Utwórz komentarz
Tworzy nowy komentarz do quizu. Uwierzytelniony użytkownik zostanie przypisany do komentarza.

*Parametry ścieżki:*
- `quizId` (integer) - ID quizu

*Body (JSON):*
```json
{
  "content": "Świetny quiz, polecam!",
  "rating": 5
}
```

*Odpowiedzi:*
- `201` - Komentarz został utworzony
- `404` - Quiz nie znaleziony
- `422` - Walidacja nie powiodła się

*Przykład odpowiedzi (201):*
```json
{
  "id": 1,
  "quiz_id": 1,
  "user_id": 5,
  "user_name": "Jan Kowalski",
  "content": "Świetny quiz, polecam!",
  "rating": 5,
  "created_at": "2026-01-27T17:22:57.095Z",
  "updated_at": "2026-01-27T17:22:57.095Z"
}
```

#line(length: 100%, stroke: 1pt + luma(200))

=== GET `/api/quizzes/{quizId}/comments/rating` - Średnia ocena quizu
Pobiera średnią ocenę i liczbę komentarzy dla danego quizu.

*Parametry ścieżki:*
- `quizId` (integer) - ID quizu

*Odpowiedzi:*
- `200` - Średnia ocena i liczba komentarzy
- `404` - Quiz nie znaleziony

*Przykład odpowiedzi (200):*
```json
{
  "quiz_id": 1,
  "average_rating": 4.5,
  "total_comments": 12
}
```

#line(length: 100%, stroke: 1pt + luma(200))

=== GET `/api/quizzes/{quizId}/comments/{commentId}` - Szczegóły komentarza
Pobiera pełne informacje o konkretnym komentarzu.

*Parametry ścieżki:*
- `quizId` (integer) - ID quizu
- `commentId` (integer) - ID komentarza

*Odpowiedzi:*
- `200` - Szczegóły komentarza
- `404` - Komentarz lub quiz nie znaleziony

*Przykład odpowiedzi (200):*
```json
{
  "id": 1,
  "quiz_id": 1,
  "user_id": 5,
  "user_name": "Jan Kowalski",
  "content": "Świetny quiz!",
  "rating": 5,
  "created_at": "2026-01-27T17:22:57.099Z",
  "updated_at": "2026-01-27T17:22:57.099Z"
}
```

#line(length: 100%, stroke: 1pt + luma(200))
#pagebreak()
=== PUT `/api/quizzes/{quizId}/comments/{commentId}` - Zaktualizuj komentarz
Aktualizuje treść lub ocenę komentarza. Tylko właściciel komentarza może go edytować.

*Parametry ścieżki:*
- `quizId` (integer)
- `commentId` (integer)

*Body (JSON):*
```json
{
  "content": "Zaktualizowana treść",
  "rating": 4
}
```

*Odpowiedzi:*
- `200` - Komentarz został zaktualizowany
- `403` - Brak uprawnień do edycji tego komentarza
- `404` - Komentarz lub quiz nie znaleziony
- `422` - Walidacja nie powiodła się

*Przykład odpowiedzi (200):*
```json
{
  "id": 1,
  "quiz_id": 1,
  "user_id": 5,
  "user_name": "Jan Kowalski",
  "content": "Zaktualizowana treść",
  "rating": 4,
  "created_at": "2026-01-27T17:22:57.103Z",
  "updated_at": "2026-01-27T17:22:57.103Z"
}
```

#line(length: 100%, stroke: 1pt + luma(200))

=== DELETE `/api/quizzes/{quizId}/comments/{commentId}` - Usuń komentarz
Usuwa komentarz. Tylko właściciel komentarza może go usunąć.

*Parametry ścieżki:*
- `quizId` (integer)
- `commentId` (integer)

*Odpowiedzi:*
- `200` - Komentarz został usunięty
- `403` - Brak uprawnień do usunięcia tego komentarza
- `404` - Komentarz lub quiz nie znaleziony

*Przykład odpowiedzi (200):*
```json
{
  "message": "Komentarz został usunięty"
}
```

#pagebreak()

== Questions

#line(length: 100%, stroke: 1pt + luma(200))

=== POST `/quizzes/{quizId}/questions` - Dodaj pytanie
Dodaje nowe pytanie do quizu (*tylko dla właściciela quizu*).

*Parametry ścieżki:*
- `quizId` (integer) - ID quizu

*Body (JSON):*
```json
{
  "text": "Jaką jest stolica Polski?",
  "points": 10,
  "image_path": "string"
}
```

*Odpowiedzi:*
- `201` - Pytanie zostało dodane
- `403` - Brak uprawnień

*Przykład odpowiedzi (201):*
```json
{
  "message": "Question added successfully",
  "question": {
    "id": 1,
    "quiz_id": 0,
    "text": "string",
    "points": 0,
    "image_path": "string"
  }
}
```

#pagebreak()

== Quiz Results

#line(length: 100%, stroke: 1pt + luma(200))

=== GET `/quiz-results` - Moje wyniki
Pobiera listę wyników quizów zalogowanego użytkownika.

*Odpowiedzi:*
- `200` - Lista wyników

*Przykład odpowiedzi (200):*
```json
[
  {
    "id": 1,
    "user_id": 1,
    "quiz_id": 1,
    "points": 85,
    "created_at": "2026-01-27T17:22:57.110Z"
  }
]
```

#line(length: 100%, stroke: 1pt + luma(200))

=== GET `/quiz-results/{id}` - Szczegóły wyniku
Pobiera szczegóły wyniku quizu z pytaniami.

*Parametry ścieżki:*
- `id` (integer) - ID wyniku

*Odpowiedzi:*
- `200` - Szczegóły wyniku
- `404` - Wynik nie znaleziony

*Przykład odpowiedzi (200):*
```json
{
  "id": 1,
  "user_id": 1,
  "quiz_id": 1,
  "points": 85,
  "total": 100,
  "created_at": "2026-01-27T17:22:57.111Z"
}
```
