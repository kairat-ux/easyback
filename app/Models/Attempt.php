<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $fillable = [
        'student_id',
        'exercise_id',
        'score',
        'max_score',
        'answers',
    ];

    protected $casts = [
        'answers' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
