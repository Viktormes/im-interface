<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Cardinality;

class DeviceConformsTo extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'category' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'device-specification-category',
            ],
            'specification' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::One,
                'valueSet' => 'device-specification-type',
            ],
            'version' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
