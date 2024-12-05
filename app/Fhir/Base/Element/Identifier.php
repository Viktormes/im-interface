<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Cardinality;

class Identifier extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'use' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'isModifier' => true,
                'valueSet' => 'identifier-use',
            ],
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'identifier-type',
            ],
            'system' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'value' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'period' => [
                'type' => Period::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'assigner' => [
                'type' => Reference::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
