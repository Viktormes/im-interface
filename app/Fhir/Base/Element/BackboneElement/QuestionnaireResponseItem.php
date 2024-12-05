<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Cardinality;

class QuestionnaireResponseItem extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'linkId' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::One,
            ],
            'definition' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'text' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'answer' => [
                'type' => QuestionnaireResponseItemAnswer::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'item' => [
                'type' => QuestionnaireResponseItem::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
