<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Period;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource\Organization;
use App\Fhir\Cardinality;

class OrganizationQualification extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'identifier' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'code' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::One,
            ],
            'period' => [
                'type' => Period::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'issuer' => [
                'type' => Reference::class,
                'target' => [
                    Organization::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
