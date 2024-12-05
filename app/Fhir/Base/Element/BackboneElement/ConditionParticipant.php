<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource\Organization;
use App\Fhir\Base\Resource\DomainResource\Patient;
use App\Fhir\Base\Resource\DomainResource\Practitioner;
use App\Fhir\Cardinality;

class ConditionParticipant extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'function' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'participation-role-type',
            ],
            'actor' => [
                'type' => Reference::class,
                'target' => [
                    Practitioner::class,
                    Patient::class,
                    Organization::class,
                    // TODO: Add missing types
                    // PractitionerRole::class,
                    // RelatedPerson::class,
                    // Device::class,
                    // CareTeam::class,
                ],
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
        ]);
    }
}
