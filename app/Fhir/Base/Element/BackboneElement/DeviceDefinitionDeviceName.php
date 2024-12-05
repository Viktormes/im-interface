<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Cardinality;

class DeviceDefinitionDeviceName extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'name' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'type' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'device-nametype',
            ],
        ]);
    }
}
