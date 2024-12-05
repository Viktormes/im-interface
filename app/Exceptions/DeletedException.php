<?php

namespace App\Exceptions;

class DeletedException extends FhirException
{
    public function __construct(string $text, int $statusCode = 410)
    {
        parent::__construct([
            'severity' => 'error',
            'code' => 'deleted',
            'details' => [
                'text' => $text,
            ],
        ], $statusCode);
    }
}
