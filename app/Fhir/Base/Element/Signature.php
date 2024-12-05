<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBase64Binary;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInstant;
use App\Fhir\Cardinality;

class Signature extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'type' => [
                'type' => Coding::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'when' => [
                'type' => FhirInstant::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'who' => [
                'type' => Reference::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'onBehalfOf' => [
                'type' => Reference::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'targetFormat' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'mimetypes',
            ],
            'sigFormat' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'mimetypes',
            ],
            'data' => [
                'type' => FhirBase64Binary::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
