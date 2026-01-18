<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'user_id');
    }

    public function statistics()
    {
        return $this->hasMany(Statystyka::class, 'user_id');
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    public function completedQuizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_results', 'user_id', 'quiz_id');
    }

    public function createdQuizzes()
    {
        return $this->hasMany(Quiz::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


}
