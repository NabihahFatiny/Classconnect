<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'different:current_password', // New password must be different from current password
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Please enter your current password.',
            'password.required' => 'Please enter a new password.',
            'password.min' => 'New password must be at least 6 characters.',
            'password.confirmed' => 'New password confirmation does not match.',
            'password.different' => 'New password must be different from your current password.',
        ];
    }
}


