<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirPositiveInt;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Cardinality;

class ContactPoint extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'system' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'contact-point-system',
            ],
            'value' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'use' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'isModifier' => true,
                'valueSet' => 'contact-point-use',
            ],
            'rank' => [
                'type' => FhirPositiveInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'period' => [
                'type' => Period::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
