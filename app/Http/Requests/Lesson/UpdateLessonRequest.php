<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "|string|max:255",
            "description" => "required|string|max:1000",
            "file_path" => "nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt",
            "subject_id" => "required|exists:subjects,id",
        ];
    }
}
