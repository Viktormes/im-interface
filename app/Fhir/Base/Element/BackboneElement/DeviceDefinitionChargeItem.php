<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableReference;
use App\Fhir\Base\Element\Period;
use App\Fhir\Base\Element\Quantity;
use App\Fhir\Base\Element\UsageContext;
use App\Fhir\Cardinality;

class DeviceDefinitionChargeItem extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'chargeItemCode' => [
                'type' => CodeableReference::class,
                'target' => [
                    // TODO: Add missing types
                    // ChargeItemDefinition::class,
                ],
                'cardinality' => Cardinality::One,
            ],
            'count' => [
                'type' => Quantity::class,
                'cardinality' => Cardinality::One,
            ],
            'effectivePeriod' => [
                'type' => Period::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'useContext' => [
                'type' => UsageContext::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
