<?php

namespace App\Fhir\Base;

use App\Fhir\Base;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\Extension;
use App\Fhir\Cardinality;

abstract class Element extends Base
{
    public function structure(): array
    {
        return [
            'id' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'extension' => [
                'type' => Extension::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ];
    }
}
