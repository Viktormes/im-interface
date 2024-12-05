<?php

namespace App\Exceptions;

class StructuralIssueException extends FhirException
{
    public function __construct(string $text, string $expression, int $statusCode = 422)
    {
        parent::__construct([
            'severity' => 'error',
            'code' => 'structure',
            'details' => [
                'text' => "Error parsing resource ($text)",
            ],
            'expression' => [
                $expression,
            ],
        ], $statusCode);
    }
}
