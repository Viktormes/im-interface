<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Cardinality;

class DeviceDefinitionMaterial extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'substance' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::One,
            ],
            'alternate' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'allergenicIndicator' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
