<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

use App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirInteger64 extends PrimitiveType
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^[0]|[-+]?[1-9][0-9]*$/',
            'min' => -9223372036854775808,
            'max' => 9223372036854775807,
        ]);
    }
}
