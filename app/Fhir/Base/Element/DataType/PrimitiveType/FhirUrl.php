<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirUrl extends FhirUri
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^\S*$/',
        ]);
    }
}
