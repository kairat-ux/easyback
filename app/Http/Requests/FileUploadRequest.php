<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file'          => 'required|file|max:10240',
            'category'      => 'nullable|string|in:lesson_material,avatar,exercise,general',
            'fileable_type' => 'nullable|string',
            'fileable_id'   => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'File must be less than 10MB',
        ];
    }
}
