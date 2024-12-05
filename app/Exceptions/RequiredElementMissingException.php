<?php

namespace App\Exceptions;

class RequiredElementMissingException extends FhirException
{
    public function __construct(string $expression, int $statusCode = 422)
    {
        parent::__construct([
            'severity' => 'error',
            'code' => 'required',
            'details' => [
                'text' => 'An element or header value is invalid.',
            ],
            'expression' => [
                $expression,
            ]
        ], $statusCode);
    }
}
