<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDecimal;
use App\Fhir\Cardinality;

class BundleEntrySearch extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'mode' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'search-entry-mode',
            ],
            'score' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
