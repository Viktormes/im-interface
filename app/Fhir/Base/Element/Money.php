<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDecimal;
use App\Fhir\Cardinality;

class Money extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'value' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'currency' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'currencies',
            ],
        ]);
    }
}
