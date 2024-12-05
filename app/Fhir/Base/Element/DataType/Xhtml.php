<?php

namespace App\Fhir\Base\Element\DataType;

use App\Fhir\Base\Element\DataType;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Cardinality;

class Xhtml extends DataType
{
    public function __construct(mixed $value = null, ?string $id = null)
    {
        $this->attributes = [
            'value' => $value,
            'id' => $id,
        ];
    }

    // Override structure since xhtml cant have extensions (still inherits from DataType though)
    public function structure(): array
    {
        return [
            'id' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ];
    }

    public function fill(mixed $data, mixed $extensionData = null): static
    {
        $this->attributes['value'] = $data;

        if ($extensionData) {
            $this->attributes['id'] = $extensionData['id'] ?? null;
        }

        return $this;
    }

    public function serialize(bool $summary = false, ?array $elements = null): array
    {
        $ext = [];
        if (null !== ($id = $this->id)) {
            $ext['id'] = $id;
        }
        if ($ext === []) {
            $ext = null;
        }

        return [$this->value, $ext];
    }
}
