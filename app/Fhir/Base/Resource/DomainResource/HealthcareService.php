<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\Attachment;
use App\Fhir\Base\Element\Availability;
use App\Fhir\Base\Element\BackboneElement\HealthcareServiceEligibility;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirMarkdown;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\ExtendedContactDetail;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class HealthcareService extends DomainResource
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
            'active' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'isModifier' => true,
            ],
            'providedBy' => [
                'type' => Reference::class,
                'target' => [
                    Organization::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'offeredIn' => [
                'type' => Reference::class,
                'target' => [
                    HealthcareService::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'category' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'service-category',
            ],
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'service-type',
            ],
            'specialty' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'c80-practice-codes',
            ],
            'location' => [
                'type' => Reference::class,
                'target' => [
                    Location::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'name' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'comment' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'extraDetails' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'photo' => [
                'type' => Attachment::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'contact' => [
                'type' => ExtendedContactDetail::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'coverageArea' => [
                'type' => Reference::class,
                'target' => [
                    Location::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'serviceProvisionCode' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'service-provision-conditions',
            ],
            'eligibility' => [
                'type' => HealthcareServiceEligibility::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'program' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'program',
            ],
            'characteristic' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'service-mode',
            ],
            'communication' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'all-languages',
            ],
            'referralMethod' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'referral-method',
            ],
            'appointmentRequired' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'availability' => [
                'type' => Availability::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'endpoint' => [
                'type' => Reference::class,
                'target' => [
                    Endpoint::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
