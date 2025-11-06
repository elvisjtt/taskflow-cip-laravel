<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassowrd implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < 8) {
            $fail('La contraseña debe tener al menos 8 caracteres');
        }
        if (!preg_match('/[a-z]/', $value)) {
            $fail('La contraseña debe tener al menos una letra minuscula');
        }
    }
}
