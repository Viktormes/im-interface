<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirCanonical extends FhirUri
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^\S*$/',
        ]);
    }
}
