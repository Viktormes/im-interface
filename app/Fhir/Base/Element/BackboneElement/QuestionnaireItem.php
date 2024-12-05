<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\Coding;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCanonical;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Cardinality;

class QuestionnaireItem extends BackboneElement
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
            'code' => [
                'type' => Coding::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'questionnaire-questions',
            ],
            'prefix' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'text' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'translatable' => true,
            ],
            'type' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'valueSet' => 'item-type',
            ],
            'enableWhen' => [
                'type' => QuestionnaireItemEnableWhen::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'isModifier' => true,
            ],
            'enableBehaviour' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'questionnaire-enable-behaviour',
            ],
            'disabledDisplay' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'questionnaire-disabled-display',
            ],
            'required' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'repeats' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'readOnly' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'maxLength' => [
                'type' => FhirInteger::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'answerConstraint' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'questionnaire-answer-constraint',
            ],
            'answerValueSet' => [
                'type' => FhirCanonical::class,
                'target' => [
                    // ValueSet::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'answerOption' => [
                'type' => QuestionnaireItemAnswerOption::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'initial' => [
                'type' => QuestionnaireItemInitial::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'item' => [
                'type' => QuestionnaireItem::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
