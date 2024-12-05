<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInstant;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Base\Resource;
use App\Fhir\Cardinality;

class BundleEntryResponse extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'status' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
            'location' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'etag' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'lastModified' => [
                'type' => FhirInstant::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'outcome' => [
                'type' => Resource::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
