<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\Availability;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\ExtendedContactDetail;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Period;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class PractitionerRole extends DomainResource
{
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
            ],
            'period' => [
                'type' => Period::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'practitioner' => [
                'type' => Reference::class,
                'target' => [
                    Practitioner::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'organization' => [
                'type' => Reference::class,
                'target' => [
                    Organization::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'code' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'practitioner-role',
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
            'healthcareService' => [
                'type' => Reference::class,
                'target' => [
                    HealthcareService::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'contact' => [
                'type' => ExtendedContactDetail::class,
                'cardinality' => Cardinality::ZeroOrMany,
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
