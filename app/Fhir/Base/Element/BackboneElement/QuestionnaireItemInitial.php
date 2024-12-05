<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\Attachment;
use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\Coding;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDate;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDecimal;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Base\Element\Quantity;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Cardinality;

class QuestionnaireItemInitial extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'value' => [
                'types' => [
                    'valueBoolean' => FhirBoolean::class,
                    'valueDecimal' => FhirDecimal::class,
                    'valueInteger' => FhirInteger::class,
                    'valueDate' => FhirDate::class,
                    'valueDateTime' => FhirDateTime::class,
                    'valueTime' => FhirTime::class,
                    'valueString' => FhirString::class,
                    'valueUri' => FhirUri::class,
                    'valueAttachment' => Attachment::class,
                    'valueCoding' => Coding::class,
                    'valueQuantity' => Quantity::class,
                    'valueReference' => Reference::class,
                ],
                'target' => [
                    // any
                ],
                'cardinality' => Cardinality::One,
                'valueSet' => 'questionnaire-answers',
                'translatable' => true,
            ],
        ]);
    }
}
