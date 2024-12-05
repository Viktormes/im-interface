<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\Annotation;
use App\Fhir\Base\Element\Attachment;
use App\Fhir\Base\Element\BackboneElement\ObservationComponent;
use App\Fhir\Base\Element\BackboneElement\ObservationReferenceRange;
use App\Fhir\Base\Element\BackboneElement\ObservationTriggeredBy;
use App\Fhir\Base\Element\BackboneType\Timing;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCanonical;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInstant;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirTime;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Period;
use App\Fhir\Base\Element\Quantity;
use App\Fhir\Base\Element\Range;
use App\Fhir\Base\Element\Ratio;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Element\SampledData;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class Observation extends DomainResource
{
    public static function searchParameters(): array
    {
        return [
            'based-on' => [
                'type' => 'reference',
                'field' => 'basedOn',
            ],
            'category' => [
                'type' => 'token',
                'field-type' => 'coding',
                'field' => 'category[*]->coding[*]',
            ],
            'code' => [
                'type' => 'token',
                'field-type' => 'coding',
                'field' => 'code->coding[*]',
            ],
            'patient' => [
                'type' => 'reference',
                'field' => 'subject',
            ],
            'subject' => [
                'type' => 'reference',
                'field' => 'subject',
            ],
            'date' => [
                'type' => 'date',
                'field' => 'effective->value',
            ],
            //TODO: Add missing search parameters
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
            'instantiates' => [
                'types' => [
                    'instantiatesCanonical' => FhirCanonical::class,
                    'instantiatesReference' => Reference::class,
                ],
                'target' => [
                    // TODO: Add missing types
                    // ObservationDefinition::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'basedOn' => [
                'type' => Reference::class,
                'target' => [
                    // TODO: Add missing types
                    // CarePlan::class,
                    // DeviceRequest::class,
                    // ImmunizationRecommendation::class,
                    // MedicationRequest::class,
                    // NutritionOrder::class,
                    // ServiceRequest::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'triggeredBy' => [
                'type' => ObservationTriggeredBy::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'partOf' => [
                'type' => Reference::class,
                'target' => [
                    // TODO: Add missing types
                    // MedicationAdministration::class,
                    // MedicationDispense::class,
                    // MedicationStatement::class,
                    // Procedure::class,
                    // Immunization::class,
                    // ImagingStudy::class,
                    // GenomicStudy::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'status' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'isModifier' => true,
                'valueSet' => 'observation-status',
            ],
            'category' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'observation-category',
            ],
            'code' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'observation-codes',
            ],
            'subject' => [
                'type' => Reference::class,
                'target' => [
                    Patient::class,
                    Organization::class,
                    Practitioner::class,
                    Location::class,
                    Device::class,
                    // TODO: Add missing types
                    // Group::class,
                    // Procedure::class,
                    // Medication::class,
                    // Substance::class,
                    // BiologicallyDerivedProduct::class,
                    // NutritionProduct::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'focus' => [
                'type' => Reference::class,
                'target' => [],
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'encounter' => [
                'type' => Reference::class,
                'target' => [
                    // TODO: Add missing types
                    // Encounter::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'effective' => [
                'types' => [
                    'effectiveDateTime' => FhirDateTime::class,
                    'effectivePeriod' => Period::class,
                    'effectiveTiming' => Timing::class,
                    'effectiveInstant' => FhirInstant::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'issued' => [
                'type' => FhirInstant::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'performer' => [
                'type' => Reference::class,
                'target' => [
                    Practitioner::class,
                    Organization::class,
                    Patient::class,
                    PractitionerRole::class,
                    // TODO: Add missing types
                    // CareTeam::class,
                    // RelatedPerson::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
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
            'note' => [
                'type' => Annotation::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'bodySite' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'body-site',
            ],
            'bodyStructure' => [
                'type' => Reference::class,
                'target' => [
                    // TODO: Add missing types
                    // BodyStructure::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'method' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'observation-method',
            ],
            'specimen' => [
                'type' => Reference::class,
                'target' => [
                    // TODO: Add missing types
                    // Specimen::class,
                    // Group::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'device' => [
                'type' => Reference::class,
                'target' => [
                    Device::class,
                    // TODO: Add missing types
                    // DeviceMetric::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'referenceRange' => [
                'type' => ObservationReferenceRange::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'hasMember' => [
                'type' => Reference::class,
                'target' => [
                    Observation::class,
                    // TODO: Add missing types
                    // QuestionnaireResponse::class,
                    // MolecularSequence::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'derivedFrom' => [
                'type' => Reference::class,
                'target' => [
                    Observation::class,
                    // TODO: Add missing types
                    // DocumentReference::class,
                    // ImagingStudy::class,
                    // ImagingSelection::class,
                    // QuestionnaireResponse::class,
                    // MolecularSequence::class,
                    // GenomicStudy::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'component' => [
                'type' => ObservationComponent::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
        ]);
    }
}
