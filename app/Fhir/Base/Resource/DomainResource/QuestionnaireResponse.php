<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\BackboneElement\QuestionnaireResponseItem;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCanonical;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class QuestionnaireResponse extends DomainResource
{
    public static function searchParameters(): array
    {
        return [];
    }

    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'identifier' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'basedOn' => [
                'type' => Reference::class,
                'target' => [
                    //CarePlan::class,
                    //ServiceRequest::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'partOf' => [
                'type' => Reference::class,
                'target' => [
                    Observation::class,
                    //Procedure::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'questionnaire' => [
                'type' => FhirCanonical::class,
                'target' => [
                    Questionnaire::class,
                ],
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'status' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'isModifier' => true,
                'valueSet' => 'questionnaire-answers-status',
            ],
            'subject' => [
                'type' => Reference::class,
                'target' => [],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'encounter' => [
                'type' => Reference::class,
                'target' => [
                    //Encounter::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'authored' => [
                'type' => FhirDateTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'author' => [
                'type' => Reference::class,
                'target' => [
                    Device::class,
                    Practitioner::class,
                    PractitionerRole::class,
                    Patient::class,
                    //RelatedPerson::class,
                    Organization::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'source' => [
                'type' => Reference::class,
                'target' => [
                    Device::class,
                    Organization::class,
                    Patient::class,
                    Practitioner::class,
                    PractitionerRole::class,
                    //RelatedPerson::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'item' => [
                'type' => QuestionnaireResponseItem::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
