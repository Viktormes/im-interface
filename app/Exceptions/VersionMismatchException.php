<?php

namespace App\Exceptions;

class VersionMismatchException extends FhirException
{
    public function __construct(int $statusCode = 412)
    {
        parent::__construct([
            'severity' => 'error',
            'code' => 'conflict',
            'details' => [
                'text' => 'Given If-Match header does not match',
            ],
        ], $statusCode);
    }
}
