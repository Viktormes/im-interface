<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableReference;
use App\Fhir\Base\Element\Coding;
use App\Fhir\Base\Resource\DomainResource\DeviceDefinition;
use App\Fhir\Cardinality;

class DeviceDefinitionLink extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'relation' => [
                'type' => Coding::class,
                'cardinality' => Cardinality::One,
                'valueSet' => 'devicedefinition-relationtype',
            ],
            'relatedDevice' => [
                'type' => CodeableReference::class,
                'target' => [
                    DeviceDefinition::class,
                ],
                'cardinality' => Cardinality::One,
            ],
        ]);
    }
}
