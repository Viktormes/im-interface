<?php

namespace App\Exceptions;

class InvalidContentException extends FhirException
{
    public function __construct(string $text, int $statusCode = 422)
    {
        parent::__construct([
            'severity' => 'error',
            'code' => 'invalid',
            'details' => [
                'text' => $text,
            ],
        ], $statusCode);
    }
}
