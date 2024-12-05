<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Cardinality;

class DeviceVersion extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'device-versiontype',
            ],
            'component' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'installDate' => [
                'type' => FhirDateTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'value' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::One,
            ],
        ]);
    }
}
