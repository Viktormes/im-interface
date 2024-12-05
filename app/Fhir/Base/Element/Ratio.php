<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\Quantity\SimpleQuantity;
use App\Fhir\Cardinality;

class Ratio extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'numerator' => [
                'type' => Quantity::class,
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
