<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirUuid extends FhirUri
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^urn:uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
        ]);
    }
}
