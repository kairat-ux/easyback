<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'vocabulary',
        'teacher_id',
    ];

    protected $casts = [
        'vocabulary' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function uploadedFiles()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
