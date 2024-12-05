<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\BackboneElement\OperationOutcomeIssue;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class OperationOutcome extends DomainResource
{
    public static function searchParameters(): array
    {
        return [];
    }

    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'issue' => [
                'type' => OperationOutcomeIssue::class,
                'cardinality' => Cardinality::OneOrMany,
                'summary' => true,
            ],
        ]);
    }
}
