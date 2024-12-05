<?php

namespace App\Fhir\Base\Element\DataType\PrimitiveType;

class FhirOid extends FhirUri
{
    public function validationRules(): array
    {
        return array_merge(parent::validationRules(), [
            'pattern' => '/^urn:oid:[0-2](\.(0|[1-9][0-9]*))+$/',
        ]);
    }
}
