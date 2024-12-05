<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirMarkdown;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Resource\DomainResource\Organization;
use App\Fhir\Base\Resource\DomainResource\Patient;
use App\Fhir\Base\Resource\DomainResource\Practitioner;
use App\Fhir\Base\Resource\DomainResource\PractitionerRole;
use App\Fhir\Cardinality;

class Annotation extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'author' => [
                'types' => [
                    'authorReference' => Reference::class,
                    'authorString' => FhirString::class,
                ],
                'target' => [
                    Practitioner::class,
                    PractitionerRole::class,
                    Patient::class,
                    Organization::class,
                    // TODO: Add missing types
                    // RelatedPerson::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'time' => [
                'type' => FhirDateTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'text' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
            ],
        ]);
    }
}
