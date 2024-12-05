<?php

namespace App\Exceptions;

class NotSupportedException extends FhirException
{
    public function __construct(string $text, int $statusCode = 403)
    {
        parent::__construct([
            'severity' => 'error',
            'code' => 'not-supported',
            'details' => [
                'text' => $text,
            ],
        ], $statusCode);
    }
}
