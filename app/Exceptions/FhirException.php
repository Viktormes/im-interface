<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FhirException extends HttpException
{
    public function __construct(
        public array $issues,
        int $statusCode = 400,
    ) {
        parent::__construct($statusCode, '', null, [
            'Content-Type' => 'application/fhir+json; fhirVersion=5.0',
        ], 0);
    }

    public function render(): JsonResponse
    {
        return new JsonResponse(
            data: [
                'resourceType' => 'OperationOutcome',
                'issue' => $this->issues,
            ],
            status: $this->getStatusCode(),
            headers: $this->getHeaders(),
            options: request()->query('_pretty') === 'true' ? JSON_PRETTY_PRINT : 0,
        );
    }
}
