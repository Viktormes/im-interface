<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\Xhtml;
use App\Fhir\Cardinality;

class Narrative extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'status' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
            ],
            'div' => [
                'type' => Xhtml::class,
                'cardinality' => Cardinality::One,
            ],
        ]);
    }
}
