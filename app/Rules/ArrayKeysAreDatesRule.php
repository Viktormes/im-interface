<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ArrayKeysAreDatesRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach (array_keys($value) as $key) {
            if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $key) || strtotime($key) === false) {
                $fail("One or more keys in '{$attribute}' is not a valid date.");
            }
        }
    }
}
