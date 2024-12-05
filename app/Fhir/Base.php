<?php

namespace App\Fhir;

use App\Exceptions\InvalidContentException;
use App\Fhir\Base\Element\Coding;
use App\Fhir\Base\Element\DataType;
use App\Fhir\Base\Resource;
use InvalidArgumentException;

abstract class Base
{
    protected static array $_cachedStructure = [];

    public array $attributes = [];

    public function __construct()
    {
        $this->reset();
    }

    protected function reset()
    {
        foreach ($this->getCachedStructure() as $k => $v) {
            $this->attributes[$k] = in_array($v['cardinality'], [
                Cardinality::ZeroOrMany,
                Cardinality::OneOrMany,
            ]) ? [] : null;
        }
    }

    public function getCachedStructure(): array
    {
        if (! array_key_exists(static::class, static::$_cachedStructure)) {
            static::$_cachedStructure[static::class] = $this->structure();
        }

        return static::$_cachedStructure[static::class];
    }

    public function structure(): array
    {
        return [];
    }

    public function validationRules(): array
    {
        return [];
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    public function &__get(string $name): mixed
    {
        $value = data_get($this->attributes, $name);

        return $value;
    }

    public function __set(string $name, mixed $value): void
    {
        if (is_scalar($value)) {
            $t = gettype($value);
            throw new InvalidArgumentException("Invalid type '$t' on ".static::class."->$name");
        }

        $struct = $this->getCachedStructure();

        if (! array_key_exists($name, $struct)) {
            throw new InvalidArgumentException("Invalid attribute '$name' on ".static::class);
        }

        if (array_key_exists('types', $struct[$name])) {
            if (is_null(array_search($v = $value::class, $struct[$name]['types']))) {
                throw new InvalidArgumentException('Attempting to set '.static::class."->$name to value of type '$v'");
            }
        } elseif (is_array($value)) {
            foreach ($value as $val) {
                if (($t = $struct[$name]['type']) !== ($v = $val::class)) {
                    throw new InvalidArgumentException('Attempting to set '.static::class."->$name to value of type '$v', expected $t");
                }
            }
        } elseif (($t = $struct[$name]['type']) !== ($v = $value::class) && ! is_subclass_of($value, $struct[$name]['type'])) {
            throw new InvalidArgumentException('Attempting to set '.static::class."->$name to value of type '$v', expected $t");
        }

        $this->attributes[$name] = $value;
    }

    public function get(string $key, mixed $default = null)
    {
        $result = $this;

        foreach (explode('.', $key) as $segment) {
            $result = $result->{$segment};
        }

        return is_array($result) ? collect($result) : (is_subclass_of($result, DataType::class) ? $result->value : $result);
    }

    public function only(array $elements): array
    {
        return $this->serialize(elements: $elements);
    }

    public function serialize(bool $summary = false, ?array $elements = null): array
    {
        $result = [];
        $structure = $this->getCachedStructure();
        $finalElements = [];

        if ($elements !== null) {
            $finalElements[] = 'meta';
            $finalElements[] = 'contained';

            foreach ($elements as $element) {
                if (str_contains($element, '.')) {
                    [$resource, $field] = explode('.', $element);

                    if ($resource === class_basename($this)) {
                        if (! array_key_exists($field, $structure)) {
                            throw new InvalidContentException("Element '$element' not present on ".class_basename($this));
                        }

                        $finalElements[] = $field;
                    }
                } else {
                    $finalElements[] = $element;

                    if (! array_key_exists($element, $structure)) {
                        throw new InvalidContentException("Element '$element' not present on ".class_basename($this));
                    }
                }
            }
        }

        if (($summary || count($finalElements)) && $this->meta) {
            $this->meta->add('tag', (new Coding)->fill([
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ObservationValue',
                'code' => 'SUBSETTED',
                'display' => 'subsetted',
            ]));
        }

        foreach ($structure as $key => $def) {
            $value = $this->{$key};

            if (is_null($value) || (is_array($value) && ! count($value))) {
                continue;
            }

            if ($summary && ! array_key_exists('summary', $def) && ! in_array($def['cardinality'], [Cardinality::One, Cardinality::OneOrMany])) {
                continue;
            }

            if (count($finalElements) && ! in_array($key, $finalElements)) {
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $val) {
                    $arrayedProp = $val->serialize($summary, $key === 'contained' ? $elements : null);

                    if (is_subclass_of($def['type'], DataType::class)) {
                        $result[$key][] = $arrayedProp[0];
                        if ($arrayedProp[1] !== null) {
                            $result["_$key"][] = $arrayedProp[1];
                        }

                        continue;
                    }

                    $result[$key][] = $arrayedProp;
                }
            } else {
                $arrayedProp = $value->serialize($summary);

                // Choice type
                if (array_key_exists('types', $def)) {
                    $keyName = array_search($value::class, $def['types']);

                    if (is_subclass_of($def['types'][$keyName], DataType::class)) {
                        $result[$keyName] = $arrayedProp[0];
                        if ($arrayedProp[1] !== null) {
                            $result["_$keyName"] = $arrayedProp[1];
                        }
                    } else {
                        $result[$keyName] = $arrayedProp;
                    }

                    continue;
                }

                // Normal type
                if (is_subclass_of($def['type'], DataType::class)) {
                    $result[$key] = $arrayedProp[0];
                    if ($arrayedProp[1] !== null) {
                        $result["_$key"] = $arrayedProp[1];
                    }

                    continue;
                }

                $result[$key] = $arrayedProp;
            }
        }

        return $result;
    }

    public function add(string $key, ...$values): static
    {
        $struct = $this->getCachedStructure();

        if (! array_key_exists($key, $struct)) {
            throw new InvalidArgumentException("Invalid element '$key' on ".static::class);
        }

        if (! is_array($this->{$key})) {
            $el = class_basename($this);
            throw new InvalidArgumentException("$el->$key is not an array");
        }

        foreach ($values as $value) {
            if (is_scalar($value) || ($value::class !== $struct[$key]['type'] && ! is_subclass_of($value, $struct[$key]['type']))) {
                $el = class_basename($this);
                throw new InvalidArgumentException("$el->$key expects values of type '{$struct[$key]['type']}'");
            }

            $this->attributes[$key][] = $value;
        }

        return $this;
    }

    public function fill(mixed $data, mixed $extensionData = null): static
    {
        $structure = $this->getCachedStructure();
        $choiceElements = collect($structure)->filter(fn ($v) => array_key_exists('types', $v))->keys()->toArray();

        if (! is_array($data)) {
            throw new InvalidArgumentException('Base::fill expects an array');
        }

        foreach ($data as $key => $value) {
            if ($key === 'resourceType') {
                if ($value !== class_basename($this)) {
                    throw new InvalidArgumentException("Invalid value '$value' for element resourceType");
                }

                continue;
            }

            if (str_starts_with($key, '_')) {
                if (! array_key_exists(substr($key, 1), $data)) {
                    throw new InvalidArgumentException("Invalid extension for value '$key'");
                }

                continue;
            }

            $choiceType = null;
            if (! array_key_exists($key, $structure)) {
                foreach ($choiceElements as $ce) {
                    if (array_key_exists($key, $structure[$ce]['types'])) {
                        $choiceType = $ce;
                        break;
                    }
                }
                if (is_null($choiceType)) {
                    throw new InvalidArgumentException("Invalid element '$key'");
                }
            }

            $type = null;
            $cardinality = null;

            if ($choiceType) {
                $type = $structure[$choiceType]['types'][$key];

                $extData = array_key_exists("_$choiceType", $data) ? $data["_$choiceType"] : null;
                $this->attributes[$choiceType] = (new $type)->fill($value, $extData);

                continue;
            }

            if (array_key_exists('types', $structure[$key])) {
                $fhirType = $structure[$key]['types'][$value['type']];
                $extData = array_key_exists('extension', $value) ? $value['extension'] : null;

                $this->attributes[$key] = (new $fhirType)->fill($value['value'], $extData);

                continue;
            }

            $type = $structure[$key]['type'];
            $cardinality = $structure[$key]['cardinality'];

            if ($cardinality === Cardinality::ZeroOrMany || $cardinality === Cardinality::OneOrMany) {
                $this->attributes[$key] = [];

                $extDatas = array_key_exists("_$key", $data) ? $data["_$key"] : [];
                foreach ($value as $i => $val) {
                    $ext = count($extDatas) > $i ? $extDatas[$i] : null;
                    $this->attributes[$key][] = (new $type)->fill($val, $ext);
                }

                continue;
            }

            if ($type === Resource::class) {
                $newType = data_get($value, 'resourceType');
                $type = "App\Fhir\Base\Resource\DomainResource\\$newType";
            }

            $extData = array_key_exists("_$key", $data) ? $data["_$key"] : null;
            $this->attributes[$key] = (new $type)->fill($value, $extData);
        }

        return $this;
    }

    public function serializeForSaving(): array
    {
        $result = [];

        $result['resourceType'] = class_basename($this);

        foreach ($this->getCachedStructure() as $key => $def) {
            $value = $this->{$key};

            if (is_null($value) || (is_array($value) && ! count($value))) {
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $val) {
                    $arrayedProp = $val->serialize();

                    if (is_subclass_of($def['type'], DataType::class)) {
                        $result[$key][] = $arrayedProp[0];
                        if ($arrayedProp[1] !== null) {
                            $result["_$key"][] = $arrayedProp[1];
                        }

                        continue;
                    }

                    $result[$key][] = $arrayedProp;
                }
            } else {
                $arrayedProp = $value->serialize();

                // Choice type
                if (array_key_exists('types', $def)) {
                    $keyName = array_search($value::class, $def['types']);

                    if (is_subclass_of($def['types'][$keyName], DataType::class)) {
                        $result[$key] = [
                            'type' => $keyName,
                            'value' => $arrayedProp[0],
                        ];

                        if ($arrayedProp[1] !== null) {
                            $result[$key]['extension'] = $arrayedProp[1];
                        }
                    } else {
                        $result[$key] = [
                            'type' => $keyName,
                            'value' => $arrayedProp,
                        ];
                    }

                    continue;
                }

                // Normal type
                if (is_subclass_of($def['type'], DataType::class)) {
                    $result[$key] = $arrayedProp[0];
                    if ($arrayedProp[1] !== null) {
                        $result["_$key"] = $arrayedProp[1];
                    }

                    continue;
                }

                $result[$key] = $arrayedProp;
            }
        }

        return $result;
    }
}
