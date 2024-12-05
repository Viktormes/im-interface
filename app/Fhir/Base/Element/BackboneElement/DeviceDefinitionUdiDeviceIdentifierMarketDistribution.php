<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Base\Element\Period;
use App\Fhir\Cardinality;

class DeviceDefinitionUdiDeviceIdentifierMarketDistribution extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'marketPeriod' => [
                'type' => Period::class,
                'cardinality' => Cardinality::One,
            ],
            'subJurisdiction' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::One,
            ],
        ]);
    }
}
