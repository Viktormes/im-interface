<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\Address;
use App\Fhir\Base\Element\Availability;
use App\Fhir\Base\Element\BackboneElement\LocationPosition;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\Coding;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirMarkdown;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\ExtendedContactDetail;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Element\VirtualServiceDetail;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class Location extends DomainResource
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
            'status' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'isModifier' => true,
                'valueSet' => 'location-status',
            ],
            'operationalStatus' => [
                'type' => Coding::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'hl7VS-bedStatus',
            ],
            'name' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'alias' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'description' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'mode' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'location-mode',
            ],
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'ServiceDeliveryLocationRoleType',
            ],
            'contact' => [
                'type' => ExtendedContactDetail::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'address' => [
                'type' => Address::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'form' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'location-form',
            ],
            'position' => [
                'type' => LocationPosition::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'managingOrganization' => [
                'type' => Reference::class,
                'target' => [
                    Organization::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'partOf' => [
                'type' => Reference::class,
                'target' => [
                    Location::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'characteristic' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'location-characteristic',
            ],
            'hoursOfOperation' => [
                'type' => Availability::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'virtualService' => [
                'type' => VirtualServiceDetail::class,
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
