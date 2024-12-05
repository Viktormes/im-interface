<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Cardinality;

class OperationOutcomeIssue extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'severity' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'issue-severity',
            ],
            'code' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'issue-type',
            ],
            'details' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'operation-outcome',
            ],
            'diagnostics' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'location' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'expression' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
        ]);
    }
}
