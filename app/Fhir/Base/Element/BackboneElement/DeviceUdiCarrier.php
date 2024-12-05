<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBase64Binary;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Cardinality;

class DeviceUdiCarrier extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'deviceIdentifier' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'issuer' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'jurisdiction' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'carrierAIDC' => [
                'type' => FhirBase64Binary::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'carrierHRF' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'entryType' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'udi-entry-type',
            ],
        ]);
    }
}
