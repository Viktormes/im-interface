<?php

namespace App\Exceptions;

class ElementValueInvalidException extends FhirException
{
    public function __construct(mixed $value, string $prop, string $expression, string $expectedType, int $statusCode = 422)
    {
        $value = match (true) {
            is_null($value) => 'null',
            is_bool($value) => $value ? 'true' : 'false',
            is_array($value) => json_encode($value),
            is_string($value) => "'$value'",
            default => $value,
        };

        if (mb_strlen($value) > 64) {
            $value = mb_substr($value, 0, 64).'...';
        }

        parent::__construct([
            'severity' => 'error',
            'code' => 'value',
            'details' => [
                'text' => "Unknown Content ($value) at '$prop', expected value of type '$expectedType'",
            ],
            'expression' => [
                $expression,
            ],
        ], $statusCode);
    }
}
