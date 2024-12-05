<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Cardinality;

class DeviceDefinitionUdiDeviceIdentifier extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
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
            'marketDistribution' => [
                'type' => DeviceDefinitionUdiDeviceIdentifierMarketDistribution::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
