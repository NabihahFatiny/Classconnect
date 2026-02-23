<?php

namespace App\Http\Requests\Comment;

use App\Rules\NoProfanity;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'content' => ['required', 'string', 'min:3', 'max:1000', new NoProfanity],
            'discussion_id' => ['required', 'exists:discussions,id'],
            'parent_id' => ['nullable', 'exists:comments,id'],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // 2MB max
                'dimensions:max_width=2000,max_height=2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Please provide a comment.',
            'content.min' => 'Comment must be at least 3 characters.',
            'content.max' => 'Comment cannot exceed 1000 characters.',
            'content.*' => '⚠️ Warning: Your comment contains inappropriate language. Please use respectful language.',
            'discussion_id.required' => 'Discussion is required.',
            'discussion_id.exists' => 'The selected discussion does not exist.',
            'photo.image' => 'The uploaded file must be an image.',
            'photo.mimes' => 'Image must be jpeg, png, jpg, gif, or webp format.',
            'photo.max' => 'Image size cannot exceed 2MB.',
            'photo.dimensions' => 'Image dimensions cannot exceed 2000x2000 pixels.',
        ];
    }
}
