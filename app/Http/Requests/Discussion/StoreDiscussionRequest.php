<?php

namespace App\Http\Requests\Discussion;

use App\Rules\NoProfanity;
use Illuminate\Foundation\Http\FormRequest;

class StoreDiscussionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        // Log the validation failure
        \Log::info('StoreDiscussionRequest validation failed', [
            'errors' => $validator->errors()->toArray(),
            'error_bag' => $this->errorBag,
        ]);

        // Manually create redirect response with errors and input
        $redirect = redirect()->route('discussions.create')
            ->withErrors($validator->errors(), $this->errorBag)
            ->withInput();

        // Log that we're redirecting
        \Log::info('Redirecting to discussions.create with errors', [
            'errors_count' => $validator->errors()->count(),
            'session_errors' => session('errors'),
        ]);

        // Throw ValidationException which will be caught by Laravel's exception handler
        // The redirect response will be used
        throw (new \Illuminate\Validation\ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo(route('discussions.create'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Log what we received before validation
        \Log::info('StoreDiscussionRequest rules() called', [
            'title' => $this->input('title'),
            'title_length' => strlen($this->input('title', '')),
            'content_length' => strlen($this->input('content', '')),
            'method' => $this->method(),
        ]);

        $rules = [
            'title' => ['required', 'string', 'max:255', 'min:3', new NoProfanity],
            'content' => ['required', 'string', 'min:10', 'max:5000', new NoProfanity],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // 2MB max
                'dimensions:max_width=2000,max_height=2000', // Prevent oversized images
            ],
            'class' => ['nullable', 'string', 'in:1A,1B'],
        ];

        return $rules;
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
