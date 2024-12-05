<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Cardinality;

class CodeableConcept extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'coding' => [
                'type' => Coding::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'text' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'translatable' => true,
            ],
        ]);
    }
}
