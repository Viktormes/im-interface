<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

use App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirInteger extends PrimitiveType
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^[0]|[-+]?[1-9][0-9]*$/',
            'min' => -2147483648,
            'max' => 2147483647,
        ]);
    }
}
