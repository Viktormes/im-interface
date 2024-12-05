<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource\Organization;
use App\Fhir\Cardinality;

class DeviceDefinitionPackagingDistributor extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'name' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'organizationReference' => [
                'type' => Reference::class,
                'target' => [
                    Organization::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
