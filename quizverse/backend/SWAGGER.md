# Dokumentacja Swagger - QuizVerse API

## Przegląd
Dokumentacja API QuizVerse jest dostępna przy użyciu OpenAPI 3.0 i Swagger UI.

## Dostęp do Dokumentacji

### Swagger UI
Graficzny interfejs dokumentacji dostępny pod adresem:
```
http://localhost:8000/api/documentation
```

### JSON OpenAPI
Plik JSON z pełną specyfikacją dostępny pod adresem:
```
http://localhost:8000/api/documentation.json
```

## Instalacja Swagger

### 1. Zainstaluj bibliotekę (jeśli nie zainstalowana)
```bash
composer require darkaonline/l5-swagger
```

### 2. Wygeneruj dokumentację
```bash
php artisan l5-swagger:generate
```

## Struktura Dokumentacji

### Endpointy Komentarzy (`/api/quizzes/{quizId}/comments`)

#### 1. **GET** - Lista komentarzy do quizu
```
GET /api/quizzes/{quizId}/comments
```
- **Parametry**: `quizId` (integer)
- **Odpowiedź**: Tablica komentarzy posortowanych od najnowszych
- **Wymagane**: Bearer Token

#### 2. **POST** - Utwórz nowy komentarz
```
POST /api/quizzes/{quizId}/comments
```
- **Parametry**: `quizId` (integer)
- **Body JSON**:
  ```json
  {
    "content": "Świetny quiz!",
    "rating": 5
  }
  ```
- **Wymagane**: Bearer Token
- **Walidacja**: 
  - `content`: string, minimum 3 znaki, maksimum 1000 znaków
  - `rating`: integer od 1 do 5

#### 3. **GET** - Średnia ocena quizu
```
GET /api/quizzes/{quizId}/comments/rating
```
- **Parametry**: `quizId` (integer)
- **Odpowiedź**:
  ```json
  {
    "quiz_id": 1,
    "average_rating": 4.5,
    "total_comments": 12
  }
  ```
- **Wymagane**: Bearer Token

#### 4. **GET** - Pobierz konkretny komentarz
```
GET /api/quizzes/{quizId}/comments/{commentId}
```
- **Parametry**: `quizId` (integer), `commentId` (integer)
- **Odpowiedź**: Szczegóły komentarza
- **Wymagane**: Bearer Token

#### 5. **PUT** - Zaktualizuj komentarz
```
PUT /api/quizzes/{quizId}/comments/{commentId}
```
- **Parametry**: `quizId` (integer), `commentId` (integer)
- **Body JSON**: (opcjonalne pola)
  ```json
  {
    "content": "Zaktualizowana treść",
    "rating": 4
  }
  ```
- **Wymagane**: Bearer Token (tylko właściciel)
- **Błędy**: 
  - 403 - Brak uprawnień
  - 404 - Nie znaleziono

#### 6. **DELETE** - Usuń komentarz
```
DELETE /api/quizzes/{quizId}/comments/{commentId}
```
- **Parametry**: `quizId` (integer), `commentId` (integer)
- **Wymagane**: Bearer Token (tylko właściciel)
- **Odpowiedź**:
  ```json
  {
    "message": "Komentarz został usunięty"
  }
  ```

## Autentykacja

Wszystkie endpointy wymagają Bearer Token autoryzacji. 

### Uzyskanie tokenu:
```
POST /api/login
Body: {
  "email": "user@example.com",
  "password": "password"
}
```

### Użycie tokenu:
```
Headers:
Authorization: Bearer your_token_here
```

## Anonimowi Użytkownicy

Komentarze mogą być tworzone przez:
- **Zalogowanych użytkowników**: `user_id` zawiera ID użytkownika
- **Anonimowych użytkowników**: `user_id` = null lub 0, wyświetlane jako "Anonimowy użytkownik"

## Kody Odpowiedzi HTTP

| Kod | Opis |
|-----|------|
| 200 | Sukces |
| 201 | Zasób utworzony |
| 400 | Błąd żądania |
| 401 | Brak autentykacji |
| 403 | Brak uprawnień |
| 404 | Nie znaleziono |
| 422 | Błąd walidacji |
| 500 | Błąd serwera |

## Schematy

### Comment
```json
{
  "id": 1,
  "quiz_id": 1,
  "user_id": 5,
  "user_name": "Jan Kowalski",
  "content": "Świetny quiz!",
  "rating": 5,
  "created_at": "2025-01-10T10:30:00.000000Z",
  "updated_at": "2025-01-10T10:30:00.000000Z"
}
```

## Troubleshooting

### Dokumentacja nie wyświetla się
1. Upewnij się, że serwer Laravel działa
2. Wygeneruj dokumentację:
   ```bash
   php artisan l5-swagger:generate
   ```
3. Wyczyść cache:
   ```bash
   php artisan cache:clear
   ```

### Problemy z tokenem
- Upewnij się, że token nie wygasł
- Sprawdź, czy format nagłówka jest poprawny: `Bearer token_value`

## Dodatkowe Zasoby

- [L5-Swagger Dokumentacja](https://github.com/DarkaOnline/L5-Swagger)
- [OpenAPI 3.0 Specification](https://spec.openapis.org/oas/v3.0.0)
- [Swagger.io](https://swagger.io/)
