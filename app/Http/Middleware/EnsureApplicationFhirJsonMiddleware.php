<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnsureApplicationFhirJsonMiddleware
{
    const SUPPORTED_VERSIONS = ['5.0', '5'];

    public function handle(Request $request, Closure $next)
    {
        if (! $request->accepts(['application/fhir+json', 'application/json', 'text/json'])) {
            return $this->errorResponse('not-supported', 'Not Acceptable', 406);
        }

        if (preg_match('/fhirVersion=(\d+(?:\.\d+)?)/', $request->header('Accept'), $matches)) {
            if (! in_array($matches[1], static::SUPPORTED_VERSIONS)) {
                return $this->errorResponse('not-supported', 'Unsupported fhirVersion', 406);
            }
        }

        return $next($request);
    }

    private function errorResponse($type, $text, $code): JsonResponse
    {
        return response()
            ->json(
                [
                    'resourceType' => 'OperationOutcome',
                    'issue' => [
                        [
                            'severity' => 'error',
                            'code' => $type,
                            'details' => [
                                'text' => $text,
                            ],
                        ],
                    ],
                ],
                $code,
                [],
                request()->query('_pretty') === 'true' ? JSON_PRETTY_PRINT : 0
            );
    }
}
