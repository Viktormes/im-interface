<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\Coding;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDate;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirTime;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Cardinality;

class QuestionnaireItemAnswerOption extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'value' => [
                'types' => [
                    'valueInteger' => FhirInteger::class,
                    'valueDate' => FhirDate::class,
                    'valueTime' => FhirTime::class,
                    'valueString' => FhirString::class,
                    'valueCoding' => Coding::class,
                    'valueReference' => Reference::class,
                ],
                'target' => [
                    // any
                ],
                'cardinality' => Cardinality::One,
                'valueSet' => 'questionnaire-answers',
                'translatable' => true,
            ],
            'initialSelected' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
