<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled in the controller
        return auth()->check() && (auth()->user()->user_type ?? '') === 'lecturer';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'file_path' => 'nullable|array',
            'file_path.*' => 'nullable|mimes:pdf,doc,docx,ppt,pptx,txt',
            'subject_id' => 'required|exists:subjects,id',
        ];

    }

    public function messages(): array
    {
        return [
            'title.required' => 'The lesson title is required.',
            'description.required' => 'The lesson description is required.',
            'file_path.file' => 'The uploaded file must be a valid file.',
            'file_path.mimes' => 'The uploaded file must be a file of type: pdf, doc, docx, ppt, pptx, txt.',
            'subject_id.required' => 'The subject is required.',
        ];
    }
}
