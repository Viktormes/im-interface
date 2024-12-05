<?php

namespace App\Fhir\Base\Element\BackboneElement;

use App\Fhir\Base\Element\BackboneElement;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Base\Resource;
use App\Fhir\Cardinality;

class BundleEntry extends BackboneElement
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'fullUrl' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'resource' => [
                'type' => Resource::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'search' => [
                'type' => BundleEntrySearch::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'request' => [
                'type' => BundleEntryRequest::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'response' => [
                'type' => BundleEntryResponse::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }

    public function serialize(bool $summary = false, ?array $elements = null): array
    {
        $result = [];

        foreach (['fullUrl'] as $prop) {
            if ($this->{$prop} !== null) {
                $arrayedProp = $this->{$prop}->serialize($summary);

                $result[$prop] = $arrayedProp[0];
                if ($arrayedProp[1] !== null) {
                    $result["_$prop"] = $arrayedProp[1];
                }
            }
        }

        if ($this->resource !== null) {
            $result['resource'] = $this->resource->serialize($summary, $this->search?->mode?->value === 'match' ? $elements : null);
        }

        foreach (['search', 'request', 'response'] as $prop) {
            if ($this->{$prop} !== null) {
                $result[$prop] = $this->{$prop}->serialize($summary);
            }
        }

        return $result;
    }
}
