<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Resource\DomainResource\HealthcareService;
use App\Fhir\Base\Resource\DomainResource\Location;
use App\Fhir\Base\Resource\DomainResource\Organization;
use App\Fhir\Cardinality;

class UsageContext extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'code' => [
                'type' => Coding::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'value' => [
                'types' => [
                    'valueCodeableConcept' => CodeableConcept::class,
                    'valueQuantity' => Quantity::class,
                    'valueRange' => Range::class,
                    'valueReference' => Reference::class,
                ],
                'target' => [
                    Organization::class,
                    Location::class,
                    HealthcareService::class,
                    // TODO: Add missing types
                    // PlanDefinition::class,
                    // ResearchStudy::class,
                    // InsurancePlan::class,
                    // Group::class,
                ],
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
        ]);
    }
}
