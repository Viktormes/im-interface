<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\Coding;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDate;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDecimal;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirTime;
use App\Fhir\Base\Element\Quantity;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Cardinality;

class QuestionnaireItemEnableWhen extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'question' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::One,
            ],
            'operator' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'valueSet' => 'questionnaire-enable-operator',
            ],
            'answer' => [
                'types' => [
                    'answerBoolean' => FhirBoolean::class,
                    'answerDecimal' => FhirDecimal::class,
                    'answerInteger' => FhirInteger::class,
                    'answerDate' => FhirDate::class,
                    'answerDateTime' => FhirDateTime::class,
                    'answerTime' => FhirTime::class,
                    'answerString' => FhirString::class,
                    'answerCoding' => Coding::class,
                    'answerQuantity' => Quantity::class,
                    'answerReference' => Reference::class,
                ],
                'target' => [
                    // any
                ],
                'cardinality' => Cardinality::One,
                'valueSet' => 'questionnaire-answers',
            ],
        ]);
    }
}
