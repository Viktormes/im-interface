<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCanonical;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDate;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirMarkdown;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Cardinality;

class RelatedArtifact extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'type' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'related-artifact-type',
            ],
            'classifier' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'label' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'display' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'citation' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'document' => [
                'type' => Attachment::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'resource' => [
                'type' => FhirCanonical::class,
                'target' => [],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'resourceReference' => [
                'type' => Reference::class,
                'target' => [],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'publicationStatus' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'publication-status',
            ],
            'publicationDate' => [
                'type' => FhirDate::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
