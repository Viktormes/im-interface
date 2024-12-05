<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Cardinality;

abstract class BackboneType extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'modifierExtension' => [
                'type' => Extension::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'isModifier' => true,
            ],
        ]);
    }
}
