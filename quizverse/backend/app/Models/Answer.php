<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'text', 'is_correct', 'image_path'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return url('storage/' . $this->image_path);
        }
        return null;
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}