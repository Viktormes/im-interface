<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirTime;
use App\Fhir\Cardinality;

class AvailabilityAvailableTime extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'daysOfWeek' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'days-of-week',
            ],
            'allDay' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'availableStartTime' => [
                'type' => FhirTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'availableEndTime' => [
                'type' => FhirTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
