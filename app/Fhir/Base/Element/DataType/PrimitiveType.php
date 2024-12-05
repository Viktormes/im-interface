<?php

namespace App\Fhir\Base\Element\DataType;

use App\Fhir\Base\Element\DataType;
use App\Fhir\Base\Element\Extension;

abstract class PrimitiveType extends DataType
{
    public function __construct(mixed $value = null, ?string $id = null, array $extension = [])
    {
        $this->attributes = [
            'value' => $value,
            'id' => $id,
            'extension' => $extension,
        ];
    }

    public function __set(string $name, mixed $value): void
    {
        if (! in_array($name, ['value', 'id', 'extension'])) {
            throw new \InvalidArgumentException("Invalid attribute '$name' on ".static::class);
        }

        $this->attributes[$name] = $value;
    }

    public function fill(mixed $data, mixed $extensionData = null): static
    {
        $this->attributes['value'] = $data;

        if ($extensionData) {
            $this->attributes['id'] = $extensionData['id'] ?? null;
            foreach ($extensionData['extension'] ?? [] as $extension) {
                $this->attributes['extension'][] = (new Extension)->fill($extension);
            }
            // $this->attributes['extension'] = $extensionData['extension'] ?? [];
        }

        return $this;
    }

    public function serialize(bool $summary = false, ?array $elements = null): array
    {
        $ext = [];
        if (null !== ($id = $this->id)) {
            $ext['id'] = $id;
        }
        if ([] !== ($extension = $this->extension)) {
            $ext['extension'] = array_map(function (Extension $extension) {
                return $extension->serialize();
            }, $extension);
        }
        if ($ext === []) {
            $ext = null;
        }

        return [$this->value, $ext];
    }
}
