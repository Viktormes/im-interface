<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Cardinality;

class ContactDetail extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'name' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'telecom' => [
                'type' => ContactPoint::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
        ]);
    }
}
