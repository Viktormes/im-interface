<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource\DeviceDefinition;
use App\Fhir\Cardinality;

class DeviceDefinitionHasPart extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'reference' => [
                'type' => Reference::class,
                'target' => [
                    DeviceDefinition::class,
                ],
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'count' => [
                'type' => FhirInteger::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
