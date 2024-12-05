<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource\Observation;
use App\Fhir\Cardinality;

class ConditionStage extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'summary' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'condition-stage',
            ],
            'assessment' => [
                'type' => Reference::class,
                'target' => [
                    Observation::class,
                    // TODO: Add missing types
                    // ClinicalImpression::class,
                    // DiagnosticReport::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'condition-stage-type',
            ],
        ]);
    }
}
