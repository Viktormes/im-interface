<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Cardinality;

class DeviceDefinitionVersion extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'component' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'value' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::One,
            ],
        ]);
    }
}
