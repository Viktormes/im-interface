<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirMarkdown;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\RelatedArtifact;
use App\Fhir\Base\Element\UsageContext;
use App\Fhir\Cardinality;

class DeviceDefinitionGuideline extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'useContext' => [
                'type' => UsageContext::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'useInstruction' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'relatedArtifact' => [
                'type' => RelatedArtifact::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'indication' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'contraindication' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'warning' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'intendedUse' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
