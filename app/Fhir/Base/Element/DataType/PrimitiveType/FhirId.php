<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirId extends FhirString
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^[A-Za-z0-9\-\.]{1,64}$/',
        ]);
    }
}
