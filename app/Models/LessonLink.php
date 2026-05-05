<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonLink extends Model
{
    protected $fillable = ['lesson_id', 'user_id', 'title', 'url'];

    public function lesson() { return $this->belongsTo(Lesson::class); }
    public function user()   { return $this->belongsTo(User::class); }
}
