<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\Annotation;
use App\Fhir\Base\Element\BackboneElement\ConditionParticipant;
use App\Fhir\Base\Element\BackboneElement\ConditionStage;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\CodeableReference;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Period;
use App\Fhir\Base\Element\Quantity\Age;
use App\Fhir\Base\Element\Range;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class Condition extends DomainResource
{
    public static function searchParameters(): array
    {
        return [
            'abatement-age' => [
                'type' => 'quantity',
                'field' => 'abatement',
            ],
            'abatement-date' => [
                'type' => 'date',
                'field' => 'abatement',
            ],
            'abatement-string' => [
                'type' => 'string',
                'field' => 'abatement',
            ],
            'body-site' => [
                'type' => 'token',
                'field-type' => 'coding',
                'field' => 'bodySite[*]->coding[*]',
            ],
            'category' => [
                'type' => 'token',
                'field-type' => 'coding',
                'field' => 'category[*]->coding[*]',
            ],
            'clinical-status' => [
                'type' => 'token',
                'field-type' => 'coding',
                'field' => 'clinicalStatus->coding[*]',
            ],
            'code' => [
                'type' => 'token',
                'field-type' => 'coding',
                'field' => 'code->coding[*]',
            ],
            'encounter' => [
                'type' => 'reference',
                'field' => 'encounter',
            ],
            'evidence' => [
                'type' => 'token',
                'field-type' => 'coding',
                'field' => 'evidence[*]->concept->coding[*]',
            ],
            'identifier' => [
                'type' => 'token',
                'field-type' => 'identifier',
                'field' => 'identifier[*]',
            ],
            'onset-age' => [
                'type' => 'quantity',
                'field' => 'onset',
            ],
            'onset-date' => [
                'type' => 'date',
                'field' => 'onset',
            ],
            'onset-string' => [
                'type' => 'string',
                'field' => 'onset',
            ],
            'participant-actor' => [
                'type' => 'reference',
                'field' => 'participant[*]->actor',
            ],
            'participant-function' => [
                'type' => 'token',
                'field-type' => 'coding',
                'field' => 'participant[*]->function->coding[*]',
            ],
            'patient' => [
                'type' => 'reference',
                'field' => 'subject',
            ],
            'recorded-date' => [
                'type' => 'date',
                'field' => 'recordedDate',
            ],
            'severity' => [
                'type' => 'token',
                'field-type' => 'coding',
                'field' => 'severity->coding[*]',
            ],
            'stage' => [
                'type' => 'token',
                'field-type' => 'coding',
                'field' => 'stage[*]->summary->coding[*]',
            ],
            'subject' => [
                'type' => 'reference',
                'field' => 'subject',
            ],
            'verification-status' => [
                'type' => 'code',
                'field-type' => 'coding',
                'field' => 'verificationStatus->coding[*]',
            ],
        ];
    }

    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'identifier' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'clinicalStatus' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::One,
                'isModifier' => true,
                'valueSet' => 'condition-clinical',
            ],
            'verificationStatus' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'isModifier' => true,
                'valueSet' => 'condition-ver-status',
            ],
            'category' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'condition-category',
            ],
            'severity' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'condition-severity',
            ],
            'code' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'condition-code',
            ],
            'bodySite' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'body-site',
            ],
            'subject' => [
                'type' => Reference::class,
                'target' => [
                    Patient::class,
                    // TODO: Add mising types
                    // Group::class,
                ],
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'encounter' => [
                'type' => Reference::class,
                'target' => [
                    // TODO: Add mising types
                    // Encounter::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'onset' => [
                'types' => [
                    'onsetDateTime' => FhirDateTime::class,
                    'onsetAge' => Age::class,
                    'onsetPeriod' => Period::class,
                    'onsetRange' => Range::class,
                    'onsetString' => FhirString::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'abatement' => [
                'types' => [
                    'abatementDateTime' => FhirDateTime::class,
                    'abatementAge' => Age::class,
                    'abatementPeriod' => Period::class,
                    'abatementRange' => Range::class,
                    'abatementString' => FhirString::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'recordedDate' => [
                'type' => FhirDateTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'participant' => [
                'type' => ConditionParticipant::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'stage' => [
                'type' => ConditionStage::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'evidence' => [
                'type' => CodeableReference::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'note' => [
                'type' => Annotation::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
