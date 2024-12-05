<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Cardinality;

class Reference extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'reference' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'type' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'resource-types',
            ],
            'identifier' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'display' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'translatable' => true,
            ],
        ]);
    }
}
