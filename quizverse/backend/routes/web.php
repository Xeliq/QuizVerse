<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// Swagger UI
Route::get('/api/documentation', function () {
    return view('swagger-ui');
})->name('swagger-ui');

// JSON dokumentacji dostępny publicznie
Route::get('/storage/api-docs/{file}', function ($file) {
    $path = storage_path('api-docs/' . $file);
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
});

require __DIR__.'/auth.php';

