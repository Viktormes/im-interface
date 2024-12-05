<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\Address;
use App\Fhir\Base\Element\Attachment;
use App\Fhir\Base\Element\BackboneElement\PatientCommunication;
use App\Fhir\Base\Element\BackboneElement\PatientContact;
use App\Fhir\Base\Element\BackboneElement\PatientLink;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\ContactPoint;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDate;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger;
use App\Fhir\Base\Element\HumanName;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class Patient extends DomainResource
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
            'birthdate' => [
                'type' => 'date',
                'field' => 'birthDate->value',
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
            'general-practitioner' => [
                'type' => 'reference',
                'field' => 'generalPractitioner',
            ],
            'given' => [
                'type' => 'string',
                'field' => 'name[*]->given',
            ],
            'identifier' => [
                'type' => 'token',
                'field-type' => 'identifier',
                'field' => 'identifier[*]',
            ],
            'language' => [
                'type' => 'code',
                'method' => 'fuzzy-exact',
                'field' => 'communication[*]->language->coding[*]->code',
            ],
            'link' => [
                'type' => 'reference',
                'field' => 'link[*]->other',
            ],
            'name' => [
                'type' => 'string',
                'field' => 'name',
            ],
            'organization' => [
                'type' => 'reference',
                'field' => 'managingOrganization',
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

    public function relations(): array
    {
        return array_merge(parent::relations(), [
            'conditions' => [
                'resource' => Condition::class,
                'ref' => 'subject',
            ],
        ]);
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
            'maritalStatus' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'martial-status',
            ],
            'multipleBirth' => [
                'types' => [
                    'deceasedBoolean' => FhirBoolean::class,
                    'deceasedInteger' => FhirInteger::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'photo' => [
                'type' => Attachment::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'contact' => [
                'type' => PatientContact::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'communication' => [
                'type' => PatientCommunication::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'generalPractitioner' => [
                'type' => Reference::class,
                'target' => [
                    Organization::class,
                    Practitioner::class,
                    // TODO: Add missing types
                    // PractitionerRole::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'managingOrganization' => [
                'type' => Reference::class,
                'target' => [Organization::class],
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'link' => [
                'type' => PatientLink::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'isModifier' => true,
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
