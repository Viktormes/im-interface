<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCanonical;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirId;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInstant;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Cardinality;

class Meta extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'versionId' => [
                'type' => FhirId::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'lastUpdated' => [
                'type' => FhirInstant::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'source' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'profile' => [
                'type' => FhirCanonical::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'security' => [
                'type' => Coding::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'all-security-labels',
            ],
            'tag' => [
                'type' => Coding::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'common-tags',
            ],
        ]);
    }
}
