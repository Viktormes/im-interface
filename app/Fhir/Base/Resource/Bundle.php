<?php

namespace App\Fhir\Base\Resource;

use App\Fhir\Base\Element\BackboneElement\BundleEntry;
use App\Fhir\Base\Element\BackboneElement\BundleLink;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInstant;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUnsignedInt;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Signature;
use App\Fhir\Base\Resource;
use App\Fhir\Cardinality;

class Bundle extends Resource
{
    public static function make(array $attributes = []): static
    {
        $inst = new static;
        $inst->fill($attributes);

        return $inst;
    }

    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'identifier' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'type' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'valueSet' => 'bundle-type',
            ],
            'timestamp' => [
                'type' => FhirInstant::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'total' => [
                'type' => FhirUnsignedInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'link' => [
                'type' => BundleLink::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'entry' => [
                'type' => BundleEntry::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'signature' => [
                'type' => Signature::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'issues' => [
                'type' => Resource::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }

    public function serialize(bool $summary = false, ?array $elements = null): array
    {
        $result = ['resourceType' => 'Bundle'];

        foreach (['identifier', 'type', 'timestamp', 'total'] as $prop) {
            if ($this->{$prop} !== null) {
                $arrayedProp = $this->{$prop}->serialize($summary);

                $result[$prop] = $arrayedProp[0];
                if ($arrayedProp[1] !== null) {
                    $result["_$prop"] = $arrayedProp[1];
                }
            }
        }

        foreach ($this->link as $link) {
            $result['link'][] = $link->serialize($summary);
        }

        foreach ($this->entry as $entry) {
            $result['entry'][] = $entry->serialize($summary, $elements);
        }

        return $result;
    }
}
