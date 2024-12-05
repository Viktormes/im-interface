<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\Quantity\Duration;
use App\Fhir\Cardinality;

class ProductShelfLife extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'period' => [
                'types' => [
                    'periodDuration' => Duration::class,
                    'periodString' => FhirString::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'specialPrecautionsForStorage' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
        ]);
    }
}
