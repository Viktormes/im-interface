<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\Address;
use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\ContactPoint;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\HumanName;
use App\Fhir\Base\Element\Period;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource\Organization;
use App\Fhir\Cardinality;

class PatientContact extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'relationship' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'patient-contactrelationship',
            ],
            'name' => [
                'type' => HumanName::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'telecom' => [
                'type' => ContactPoint::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'address' => [
                'type' => Address::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'gender' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'administrative-gender',
            ],
            'organization' => [
                'type' => Reference::class,
                'target' => [Organization::class],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'period' => [
                'type' => Period::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
