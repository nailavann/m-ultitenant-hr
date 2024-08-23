<?php

namespace App\Http\Requests\Tenant\Api\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EducationInformationRequest extends FormRequest
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
            'name' => 'required',
            'department' => 'required',
            'degree' => 'required',
            'status' => 'required',
            'started_at' => 'required',
            'ended_at' => 'nullable'
        ];
    }
}
