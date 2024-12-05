<?php

namespace App\Exceptions;

class NotFoundException extends FhirException
{
    public function __construct(string $text, int $statusCode = 404)
    {
        parent::__construct([
            [
                'severity' => 'error',
                'code' => 'not-found',
                'details' => [
                    'text' => $text,
                ],
            ],
        ], $statusCode);
    }
}
