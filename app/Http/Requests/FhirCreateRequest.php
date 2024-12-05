<?php

namespace App\Http\Requests;

use App\Exceptions\InvalidContentException;
use App\Services\FhirService;
use Illuminate\Foundation\Http\FormRequest;

class FhirCreateRequest extends FormRequest
{
    public function after(FhirService $service): array
    {
        if (! $this->isJson() || empty($this->json()->all())) {
            throw new InvalidContentException('Bad request.', 400);
        }

        // $service->validateDomainResource($this->json()->all());

        return [];
    }

    public function authorize(): bool
    {
        return true;
    }
}
