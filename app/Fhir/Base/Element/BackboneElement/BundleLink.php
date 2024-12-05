<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Cardinality;

class BundleLink extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'relation' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'iana-link-relations',
            ],
            'url' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
        ]);
    }
}
