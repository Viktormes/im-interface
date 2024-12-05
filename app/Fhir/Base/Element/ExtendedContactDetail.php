<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Resource\DomainResource\Organization;
use App\Fhir\Cardinality;

class ExtendedContactDetail extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'purpose' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'name' => [
                'type' => HumanName::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'telecom' => [
                'type' => ContactPoint::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'address' => [
                'type' => Address::class,
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
            'period' => [
                'type' => Period::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
