<?php

namespace App\Http\Requests\Tenant\Api\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Şuan ki şifre boş bırakılamaz.',
            'current_password.min' => 'Şuan ki şifre en az 8 karakter olmalıdır.',
            'password.required' => 'Şifre boş bırakılamaz.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.string' => 'Şifre string olmalıdır.',
            'password.confirmed' => 'Yeni şifreler uyuşmuyor.',
        ];
    }
}
