<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'questions',
        'teacher_id',
        'lesson_id',
        'difficulty',
    ];

    protected $casts = [
        'questions' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function uploadedFiles()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }
}
