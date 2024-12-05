<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Cardinality;

class Period extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'start' => [
                'type' => FhirDateTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'end' => [
                'type' => FhirDateTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
