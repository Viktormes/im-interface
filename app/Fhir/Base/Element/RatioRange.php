<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\Quantity\SimpleQuantity;
use App\Fhir\Cardinality;

class RatioRange extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'lowNumerator' => [
                'type' => SimpleQuantity::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'highNumerator' => [
                'type' => SimpleQuantity::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'denominator' => [
                'type' => SimpleQuantity::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
