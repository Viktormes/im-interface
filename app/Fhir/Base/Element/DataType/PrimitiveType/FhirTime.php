<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

use App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirTime extends PrimitiveType
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^([01][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)(\.[0-9]{1,9})?$/',
        ]);
    }
}
