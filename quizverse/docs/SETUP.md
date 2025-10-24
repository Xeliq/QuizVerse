## 🚀 Uruchamianie projektu lokalnie

### 1. Klonowanie repozytorium

```bash
git clone https://github.com/Xeliq/quizverse.git
cd quizverse
```

### 2. Konfiguracja plików .env

Backend (backend/.env)
Utwórz plik .env na podstawie przykładowego:

```bash
cp backend/.env.example backend/.env
```
Zawartość pliku .env:

```bash
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=quizverse
DB_USERNAME=postgres
DB_PASSWORD=postgres

SESSION_DRIVER=database
```

Frontend (frontend/QuizeVerse)
Nie wymaga .env, ale możesz dodać proxy w vite.config.ts:

```bash
server: {
  proxy: {
    '/api': 'http://localhost:8000'
  }
}
```

### 3. Uruchomienie kontenerów

```bash
docker-compose up --build
```

### Dostępne usługi

Frontend - http://localhost:5173 - Aplikacja Vue
Backend	- http://localhost:8000 - API Laravel + sesje
pgAdmin	- http://localhost:5050 - GUI do PostgreSQL


### Logowanie do pgAdmin

Email: admin@example.com
Hasło: admin

Dodaj nowy serwer:
Host: db
Port: 5432
User: postgres
Hasło: postgres

### Migracje i seedy

Po uruchomieniu kontenerów:

```bash
docker exec -it laravel-app php artisan migrate
docker exec -it laravel-app php artisan db:seed
```