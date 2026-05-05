<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExerciseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'      => 'required|in:multiple_choice,fill_blank,matching',
            'questions' => 'required|array|min:1',
            'lesson_id' => 'nullable|exists:lessons,id',
        ];
    }
}
