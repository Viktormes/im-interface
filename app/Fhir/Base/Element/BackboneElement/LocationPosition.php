<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDecimal;
use App\Fhir\Cardinality;

class LocationPosition extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'longitude' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::One,
            ],
            'latitude' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::One,
            ],
            'altitude' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
