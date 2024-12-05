<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource\Observation;
use App\Fhir\Cardinality;

class ObservationTriggeredBy extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'observation' => [
                'type' => Reference::class,
                'target' => [
                    Observation::class,
                ],
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'type' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'observation-triggeredbytype',
            ],
            'reason' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
