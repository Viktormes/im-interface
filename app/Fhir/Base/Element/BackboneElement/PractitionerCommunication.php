<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Cardinality;

class PractitionerCommunication extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'language' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::One,
                'valueSet' => 'all-languages',
            ],
            'preferred' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
