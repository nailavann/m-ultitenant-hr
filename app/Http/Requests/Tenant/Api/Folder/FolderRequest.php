<?php

namespace App\Http\Requests\Tenant\Api\Folder;

use App\Rules\Base64ValidRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FolderRequest extends FormRequest
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
            'mimeType' => 'required',
            'base64' => ['required', new Base64ValidRule],
        ];
    }
}
