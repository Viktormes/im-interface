<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource\Patient;
use App\Fhir\Cardinality;

class PatientLink extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'other' => [
                'type' => Reference::class,
                'target' => [
                    Patient::class,
                    // TODO: Add missing types
                    // RelatedPerson::class,
                ],
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'type' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'link-type',
            ],
        ]);
    }
}
