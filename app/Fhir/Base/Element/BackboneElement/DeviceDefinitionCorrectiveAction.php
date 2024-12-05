<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\Period;
use App\Fhir\Cardinality;

class DeviceDefinitionCorrectiveAction extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'recall' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::One,
            ],
            'scope' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'device-correctiveactionscope',
            ],
            'period' => [
                'type' => Period::class,
                'cardinality' => Cardinality::One,
            ],
        ]);
    }
}
