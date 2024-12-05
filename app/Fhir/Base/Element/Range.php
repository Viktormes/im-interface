<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\Quantity\SimpleQuantity;
use App\Fhir\Cardinality;

class Range extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'low' => [
                'type' => SimpleQuantity::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'high' => [
                'type' => SimpleQuantity::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
