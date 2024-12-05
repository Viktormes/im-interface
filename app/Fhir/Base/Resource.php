<?php

namespace App\Fhir\Base;

use App\Fhir\Base;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirId;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Base\Element\Meta;
use App\Fhir\Cardinality;

abstract class Resource extends Base
{
    public function structure(): array
    {
        return [
            'id' => [
                'type' => FhirId::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'meta' => [
                'type' => Meta::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'implicitRules' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'isModifier' => true,
            ],
            'language' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'all-languages',
            ],
        ];
    }

    public function serialize(bool $summary = false, ?array $elements = null): array
    {
        return array_merge(['resourceType' => class_basename($this)], parent::serialize($summary, $elements));
    }
}
