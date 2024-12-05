<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirPositiveInt extends FhirInteger
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^[1-9][0-9]*$/',
            'min' => 1,
            'max' => 2147483647,
        ]);
    }
}
