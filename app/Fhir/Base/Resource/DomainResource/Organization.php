<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\BackboneElement\OrganizationQualification;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirMarkdown;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\ExtendedContactDetail;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class Organization extends DomainResource
{
    public static function searchParameters(): array
    {
        return [
            'active' => [
                'type' => 'boolean',
                'field' => 'active',
            ],
            'address' => [
                'type' => 'string',
                'field' => 'address',
            ],
            'address-city' => [
                'type' => 'string',
                'field' => 'address[*]->city',
            ],
            'address-country' => [
                'type' => 'string',
                'field' => 'address[*]->country',
            ],
            'address-postalcode' => [
                'type' => 'string',
                'field' => 'address[*]->postalCode',
            ],
            'address-state' => [
                'type' => 'string',
                'field' => 'address[*]->state',
            ],
            'address-use' => [
                'type' => 'code',
                'method' => 'fuzzy-exact',
                'field' => 'address[*]->use',
            ],
            'endpoint' => [
                'type' => 'reference',
                'field' => 'managingOrganization',
            ],
            'identifier' => [
                'type' => 'token',
                'field-type' => 'identifier',
                'field' => 'identifier[*]',
            ],
            'name' => [
                'type' => 'string',
                'field' => 'name',
            ],
            'partof' => [
                'type' => 'reference',
                'field' => 'managingOrganization',
            ],
            /*'phonetic' => [
                'type' => 'phonetic',
                'field' => 'phonetic_name',
            ],*/
            'type' => [
                'type' => 'token',
                'field-type' => 'code',
                'field' => 'type[*]->coding[*]',
            ],
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
            'active' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
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
            'contact' => [
                'type' => ExtendedContactDetail::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'partOf' => [
                'type' => Reference::class,
                'target' => [
                    Organization::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'endpoint' => [
                'type' => Reference::class,
                'target' => [
                    Endpoint::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'qualification' => [
                'type' => OrganizationQualification::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
