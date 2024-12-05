<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Cardinality;

class DeviceDefinitionPackaging extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'identifier' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'count' => [
                'type' => FhirInteger::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'distributor' => [
                'type' => DeviceDefinitionPackagingDistributor::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'udiDeviceIdentifier' => [
                'type' => DeviceDefinitionUdiDeviceIdentifier::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'packaging' => [
                'type' => DeviceDefinitionPackaging::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
