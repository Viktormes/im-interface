<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

use App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirBase64Binary extends PrimitiveType
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?$/',
        ]);
    }
}
