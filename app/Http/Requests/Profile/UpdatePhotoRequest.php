<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo' => [
                'required',
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
            'photo.required' => 'Please select a photo to upload.',
            'photo.image' => 'The uploaded file must be an image.',
            'photo.mimes' => 'Image must be jpeg, png, jpg, gif, or webp format.',
            'photo.max' => 'Image size cannot exceed 2MB.',
            'photo.dimensions' => 'Image dimensions cannot exceed 2000x2000 pixels.',
        ];
    }
}


