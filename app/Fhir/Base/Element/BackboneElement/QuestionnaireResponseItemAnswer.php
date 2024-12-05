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
use App\Fhir\Base\Element\Quantity\SimpleQuantity;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Cardinality;

class QuestionnaireResponseItemAnswer extends BackboneElement
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
                    'valueQuantity' => SimpleQuantity::class,
                    'valueReference' => Reference::class,
                ],
                'target' => [],
                'cardinality' => Cardinality::One,
            ],
            'item' => [
                'type' => QuestionnaireResponseItem::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
