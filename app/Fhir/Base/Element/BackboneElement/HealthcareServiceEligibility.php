<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirMarkdown;
use App\Fhir\Cardinality;

class HealthcareServiceEligibility extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'code' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'ServiceEligibility',
            ],
            'comment' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
