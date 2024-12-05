<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDecimal;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirPositiveInt;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUnsignedInt;
use App\Fhir\Base\Element\Quantity\Duration;
use App\Fhir\Cardinality;

class TimingRepeat extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'boundsDuration' => [
                'type' => Duration::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'boundsRange' => [
                'type' => Range::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'boundsPeriod' => [
                'type' => Period::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'count' => [
                'type' => FhirPositiveInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'countMax' => [
                'type' => FhirPositiveInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'duration' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'durationMax' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'durationUnit' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'units-of-time',
            ],
            'frequency' => [
                'type' => FhirPositiveInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'frequencyMax' => [
                'type' => FhirPositiveInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'period' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'periodMax' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'periodUnit' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'units-of-time',
            ],
            'dayOfWeek' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'days-of-week',
            ],
            'timeOfDay' => [
                'type' => FhirTime::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'when' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'event-timing',
            ],
            'offset' => [
                'type' => FhirUnsignedInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
