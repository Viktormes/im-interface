<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Cardinality;

class DeviceDefinitionRegulatoryIdentifier extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'type' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'valueSet' => 'devicedefinition-regulatory-identifier-type',
            ],
            'deviceIdentifier' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::One,
            ],
            'issuer' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::One,
            ],
            'jurisdiction' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::One,
            ],
        ]);
    }
}
