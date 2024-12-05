<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\Address;
use App\Fhir\Base\Element\BackboneElement\PractitionerCommunication;
use App\Fhir\Base\Element\BackboneElement\PractitionerQualification;
use App\Fhir\Base\Element\ContactPoint;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDate;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\HumanName;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class Practitioner extends DomainResource
{
    public static function searchParameters(): array
    {
        return [
            'active' => [
                'type' => 'boolean',
                'field' => 'active',
            ],
            'identifier' => [
                'type' => 'token',
                'field-type' => 'identifier',
                'field' => 'identifier[*]',
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
            'communication' => [
                'type' => 'token',
                'field-type' => 'coding',
                'field' => 'communication[*]->language->coding[*]',
            ],
            'death-date' => [
                'type' => 'date',
                'field' => 'deceased->value',
            ],
            'deceased' => [
                'type' => 'boolean',
                'query' => function ($query, $value, $modifier) {
                    $method = ($value === 'true' xor $modifier === 'not') ? 'where' : 'whereNot';

                    $query->{$method}(function ($q) {
                        $q->whereNotNull('resource->deceased->value')
                            ->where('resource->deceased->value', '<>', 0);
                    });
                },
            ],
            'email' => [
                'type' => 'code',
                'query' => function ($query, $value, $modifier) {
                    $method = $modifier === 'not' ? 'whereNot' : 'where';

                    $query->{$method}(function ($q) use ($value) {
                        $q->whereNotNull('resource->telecom')
                            ->where('resource->telecom[*]->system', 'LIKE', '%"email"%')
                            ->where('resource->telecom[*]->value', 'LIKE', "%\"$value\"%");
                    });
                },
            ],
            'family' => [
                'type' => 'string',
                'field' => 'name[*]->family',
            ],
            'gender' => [
                'type' => 'code',
                'field' => 'gender',
            ],
            'given' => [
                'type' => 'string',
                'field' => 'name[*]->given',
            ],
            'name' => [
                'type' => 'string',
                'field' => 'name',
            ],
            'phone' => [
                'type' => 'code',
                'query' => function ($query, $value, $modifier) {
                    $method = $modifier === 'not' ? 'whereNot' : 'where';

                    $query->{$method}(function ($q) use ($value) {
                        $q->whereNotNull('resource->telecom')
                            ->where('resource->telecom[*]->system', 'LIKE', '%"phone"%')
                            ->where('resource->telecom[*]->value', 'LIKE', "%\"$value\"%");
                    });
                },
            ],
            /*'phonetic' => [
                'type' => 'phonetic',
                'field' => 'phonetic_name',
            ],*/
            'telecom' => [
                'type' => 'code',
                'query' => function ($query, $value, $modifier) {
                    $method = $modifier === 'not' ? 'whereNot' : 'where';

                    $query->{$method}(function ($q) use ($value) {
                        $q->whereNotNull('resource->telecom')
                            ->where('resource->telecom[*]->value', 'LIKE', "%\"$value\"%");
                    });
                },
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
                'isModifier' => true,
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
            'gender' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'administrative-gender',
            ],
            'birthDate' => [
                'type' => FhirDate::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'deceased' => [
                'types' => [
                    'deceasedBoolean' => FhirBoolean::class,
                    'deceasedDateTime' => FhirDateTime::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'address' => [
                'type' => Address::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'qualification' => [
                'type' => PractitionerQualification::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'communication' => [
                'type' => PractitionerCommunication::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }

    public function nameStringified(): string
    {
        return collect($this->name)
            ->where('use.value', 'official')
            ->first()
            ->toString();
    }
}
