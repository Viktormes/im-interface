<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCanonical;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDecimal;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirPositiveInt;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\Quantity\SimpleQuantity;
use App\Fhir\Cardinality;

class SampledData extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'origin' => [
                'type' => SimpleQuantity::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'interval' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'intervalUnit' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'ucum-units',
            ],
            'factor' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'lowerLimit' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'upperLimit' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'dimensions' => [
                'type' => FhirPositiveInt::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'codeMap' => [
                'type' => FhirCanonical::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'offsets' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'data' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
