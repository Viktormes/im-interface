<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

use App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirUri extends PrimitiveType
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^\S*$/',
        ]);
    }
}
