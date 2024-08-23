<?php

namespace App\Http\Requests\Tenant\Api\Device;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DeviceRequest extends FormRequest
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
            'device_id' => 'required',
            'fcm_token' => 'required',
            'os_version' => 'nullable|string',
            'brand' => 'nullable|string',
            'device_name' => 'nullable|string',
            'model_id' => 'nullable|string',
            'os_name' => 'nullable|string',
        ];
    }
}
