<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'register_email' => 'required|email|unique:users,email',
            'register_password' => 'required|string|min:8|confirmed',
            'newsletter' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'register_email.unique' => 'An account with this email already exists.',
            'register_password.confirmed' => 'The password confirmation does not match.',
            'register_password.min' => 'Password must be at least 8 characters.',
        ];
    }
}
