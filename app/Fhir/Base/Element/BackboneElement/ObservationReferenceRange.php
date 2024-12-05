<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirMarkdown;
use App\Fhir\Base\Element\Quantity\SimpleQuantity;
use App\Fhir\Base\Element\Range;
use App\Fhir\Cardinality;

class ObservationReferenceRange extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'low' => [
                'type' => SimpleQuantity::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'high' => [
                'type' => SimpleQuantity::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'normalValue' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'observation-referencerange-normalvalue',
            ],
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'observation-referencerange-meaning',
            ],
            'appliesTo' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'referencerange-appliesto',
            ],
            'age' => [
                'type' => Range::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'text' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
