<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Cardinality;

class EndpointPayload extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'endpoint-payload-type',
            ],
            'mimeType' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'mimetypes',
            ],
        ]);
    }
}
