<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\BackboneElement\EndpointPayload;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\ContactPoint;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUrl;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Period;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class Endpoint extends DomainResource
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
                'cardinality' => Cardinality::One,
                'summary' => true,
                'isModifier' => true,
                'valueSet' => 'endpoint-status',
            ],
            'connectionType' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::OneOrMany,
                'summary' => true,
                'valueSet' => 'endpoint-connection-type',
            ],
            'name' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'description' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'environmentType' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'endpoint-environment',
            ],
            'managingOrganization' => [
                'type' => Reference::class,
                'target' => [
                    Organization::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'contact' => [
                'type' => ContactPoint::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'period' => [
                'type' => Period::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'payload' => [
                'type' => EndpointPayload::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'address' => [
                'type' => FhirUrl::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'header' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
