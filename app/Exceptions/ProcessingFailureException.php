<?php

namespace App\Exceptions;

class ProcessingFailureException extends FhirException
{
    public function __construct(string $text, int $statusCode = 422)
    {
        parent::__construct([
            'severity' => 'error',
            'code' => 'processing',
            'details' => [
                'text' => $text,
            ],
        ], $statusCode);
    }
}
