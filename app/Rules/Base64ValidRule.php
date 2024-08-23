<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class Base64ValidRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('Geçersiz dosya!');

        }

        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $value)) {
            $fail('Geçersiz dosya!!');

        }

        $decoded = base64_decode($value, true);

        if (!$decoded) {
            $fail('Geçersiz dosya!!!');
        }
    }
}
