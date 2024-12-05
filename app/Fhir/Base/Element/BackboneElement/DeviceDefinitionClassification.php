<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\RelatedArtifact;
use App\Fhir\Cardinality;

class DeviceDefinitionClassification extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'device-type',
            ],
            'justification' => [
                'type' => RelatedArtifact::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
