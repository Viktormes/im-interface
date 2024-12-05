<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Cardinality;

class CodeableReference extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'concept' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'reference' => [
                'type' => Reference::class,
                'target' => [],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
