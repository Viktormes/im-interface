<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Cardinality;

class Availability extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'availableTime' => [
                'type' => AvailabilityAvailableTime::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'notAvailableTime' => [
                'type' => AvailabilityNotAvailableTime::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
        ]);
    }
}
