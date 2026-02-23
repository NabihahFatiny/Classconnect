<?php

namespace App\Http\Requests\Discussion;

use App\Rules\NoProfanity;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscussionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'min:3', new NoProfanity],
            'content' => ['required', 'string', 'min:10', 'max:5000', new NoProfanity],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                'dimensions:max_width=2000,max_height=2000',
            ],
            'class' => ['nullable', 'string', 'in:1A,1B'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your discussion.',
            'title.min' => 'Title must be at least 3 characters.',
            'title.max' => 'Title cannot exceed 255 characters.',
            'title.noprofanity' => '⚠️ Warning: Your title contains inappropriate language. Please use respectful language.',
            'title' => '⚠️ Warning: Your title contains inappropriate language. Please use respectful language.',
            'content.required' => 'Please provide content for your discussion.',
            'content.min' => 'Content must be at least 10 characters.',
            'content.max' => 'Content cannot exceed 5000 characters.',
            'content.noprofanity' => '⚠️ Warning: Your content contains inappropriate language. Please use respectful language.',
            'content' => '⚠️ Warning: Your content contains inappropriate language. Please use respectful language.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Image must be jpeg, png, jpg, gif, or webp format.',
            'image.max' => 'Image size cannot exceed 2MB.',
            'image.dimensions' => 'Image dimensions cannot exceed 2000x2000 pixels.',
        ];
    }
}
