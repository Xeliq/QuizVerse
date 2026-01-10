<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable(); // 0 lub null = nie zalogowany
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->text('content');
            $table->integer('rating')->default(5); // ocena od 1-5
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
