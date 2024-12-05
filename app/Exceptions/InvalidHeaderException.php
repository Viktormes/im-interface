<?php

namespace App\Exceptions;

class InvalidHeaderException extends FhirException
{
    public function __construct(string $text, int $statusCode = 400)
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
