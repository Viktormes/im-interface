<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\Attachment;
use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\Quantity;
use App\Fhir\Base\Element\Range;
use App\Fhir\Cardinality;

class DeviceProperty extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::One,
                'valueSet' => 'device-property-type',
            ],
            'value' => [
                'types' => [
                    'valueQuantity' => Quantity::class,
                    'valueCodeableConcept' => CodeableConcept::class,
                    'valueString' => FhirString::class,
                    'valueBoolean' => FhirBoolean::class,
                    'valueInteger' => FhirInteger::class,
                    'valueRange' => Range::class,
                    'attachment' => Attachment::class,
                ],
                'cardinality' => Cardinality::One,
            ],
        ]);
    }
}
