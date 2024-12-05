<?php

namespace App\Fhir\Base\Element\BackboneType;

use App\Fhir\Base\Element\BackboneType;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\TimingRepeat;
use App\Fhir\Cardinality;

class Timing extends BackboneType
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'event' => [
                'type' => FhirDateTime::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'repeat' => [
                'type' => TimingRepeat::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'code' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'timing-abbreviation',
            ],
        ]);
    }
}
