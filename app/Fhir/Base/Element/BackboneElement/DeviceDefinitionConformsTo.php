<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\RelatedArtifact;
use App\Fhir\Cardinality;

class DeviceDefinitionConformsTo extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'category' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'device-specification-category',
            ],
            'specification' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'device-specification-type',
            ],
            'version' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'source' => [
                'type' => RelatedArtifact::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
