<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\Attachment;
use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirTime;
use App\Fhir\Base\Element\Period;
use App\Fhir\Base\Element\Quantity;
use App\Fhir\Base\Element\Range;
use App\Fhir\Base\Element\Ratio;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Element\SampledData;
use App\Fhir\Cardinality;

class ObservationComponent extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'code' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'observation-codes',
            ],
            'value' => [
                'types' => [
                    'valueQuantity' => Quantity::class,
                    'valueCodeableConcept' => CodeableConcept::class,
                    'valueString' => FhirString::class,
                    'valueBoolean' => FhirBoolean::class,
                    'valueInteger' => FhirInteger::class,
                    'valueRange' => Range::class,
                    'valueRatio' => Ratio::class,
                    'valueSampledData' => SampledData::class,
                    'valueTime' => FhirTime::class,
                    'valueDateTime' => FhirDateTime::class,
                    'valuePeriod' => Period::class,
                    'valueAttachment' => Attachment::class,
                    'valueReference' => Reference::class,
                ],
                'target' => [
                    // TODO: Add missing types
                    // MolecularSequence::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'dataAbsentReason' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'data-absent-reason',
            ],
            'interpretation' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'observation-interpretation',
            ],
            'referenceRange' => [
                'type' => ObservationReferenceRange::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
