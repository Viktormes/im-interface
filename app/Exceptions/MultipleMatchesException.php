<?php

namespace App\Exceptions;

class MultipleMatchesException extends FhirException
{
    public function __construct(int $statusCode = 412)
    {
        parent::__construct([
            'severity' => 'error',
            'code' => 'multiple-matches',
            'details' => [
                'text' => 'Multiple matching records were found when the operation required only one match.',
            ],
        ], $statusCode);
    }
}
