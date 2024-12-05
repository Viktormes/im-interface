<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Cardinality;

class HumanName extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'use' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'isModifier' => true,
                'valueSet' => 'name-use',
            ],
            'text' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'family' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'given' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'prefix' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'suffix' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'period' => [
                'type' => Period::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }

    public function toString(): string
    {
        if ($this->text !== null) {
            return $this->text->value;
        }

        return trim(
            implode(' ', array_map(fn ($v) => $v->value, $this->prefix)).' '.
            implode(' ', array_map(fn ($v) => $v->value, $this->given)).' '.
            $this->family->value.' '.
            implode(' ', array_map(fn ($v) => $v->value, $this->suffix))
        );
    }
}
