<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirCode extends FhirString
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^[^\s]+( [^\s]+)*$/',
        ]);
    }
}
