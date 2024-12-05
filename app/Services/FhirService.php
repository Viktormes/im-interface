<?php

namespace App\Services;

use App\Exceptions\ElementValueInvalidException;
use App\Exceptions\InvalidContentException;
use App\Exceptions\RequiredElementMissingException;
use App\Exceptions\ResourceRequiredException;
use App\Exceptions\StructuralIssueException;
use App\Exceptions\UnknownTypeException;
use App\Fhir\Base\Resource\DomainResource;
use Illuminate\Support\Collection;

class FhirService
{
    public function prepareCreatePayload(array $payload, string $resourceType): array
    {
        if (! array_key_exists('resourceType', $payload)) {
            throw new InvalidContentException('Missing element resourceType', 400);
        }

        if ($payload['resourceType'] !== $resourceType) {
            throw new InvalidContentException('Invalid value for element resourceType', 400);
        }

        if (array_key_exists('id', $payload)) {
            unset($payload['id']);
        }

        return $payload;
    }

    public function prepareUpdatePayload(array $payload, string $resourceType): array
    {
        if (! array_key_exists('resourceType', $payload)) {
            throw new InvalidContentException('Missing element resourceType', 400);
        }

        if ($payload['resourceType'] !== $resourceType) {
            throw new InvalidContentException('Invalid value for element resourceType', 400);
        }

        return $payload;
    }

    public function validateDomainResource(array|Collection $domainResource): string
    {
        $domainResource = collect($domainResource);

        [$resourceType, $resourceClass] = $this->validateResourceType($domainResource);
        //$resourceDefinition = collect((new $resourceClass)->getCachedDef());

        // $this->validateRecursive($domainResource, $resourceDefinition, [$resourceType]);

        return $resourceClass;
    }

    private function validateResourceType(Collection $domainResource): array
    {
        if (! $domainResource->has('resourceType')) {
            throw new ResourceRequiredException;
        }

        $resourceType = $domainResource->get('resourceType');

        return [$resourceType, $this->resolveResourceClass($resourceType)];
    }

    public function resolveResourceClass(string $resourceType): string
    {
        $resourceClass = "App\\Fhir\\Base\\Resource\\DomainResource\\$resourceType";

        if (! class_exists($resourceClass) || ! is_subclass_of($resourceClass, DomainResource::class)) {
            throw new UnknownTypeException($resourceType);
        }

        return $resourceClass;
    }

    /*
    private function validateRecursive(Collection $element, Collection $definition, array $tree): void
    {
        $this->hasRequiredProps($element, $definition, $tree);

        $this->doesNotHaveAdditionalParams($element, $definition, $tree);

        $toValidate = $element->except(['resourceType']);

        $toValidate->each(function ($value, $key) use ($definition, $tree): void {
            $this->validateProp($key, $value, $definition[$key], $tree);
        });
    }

    private function hasRequiredProps(Collection $element, Collection $definition, array $tree): void
    {
        $requiredProps = $definition->whereIn('cardinality', [Base::ONE, Base::ONE_OR_MANY]);

        $requiredProps->each(function ($def, $name) use ($element, $tree): void {
            if (! $element->has($name)) {
                throw new RequiredElementMissingException(implode('.', [...$tree, $name]));
            }
        });
    }

    private function doesNotHaveAdditionalParams(Collection $element, Collection $definition, array $tree): void
    {
        $element->except(['resourceType', 'id'])->keys()->each(function (string $key) use ($definition, $tree): void {
            if (! $definition->has($key)) {
                throw new StructuralIssueException("Unknown Element \"$key\"", implode('.', [...$tree, $key]));
            }
        });
    }

    private function validateProp(string $name, mixed $value, array $definition, array $tree): void
    {
        if ($value === null) {
            throw new ElementValueInvalidException($value, $name, implode('.', [...$tree, $name]));
        }

        // Element
        if ($this->isElement($definition['type'])) {
            $elementDef = (new $definition['type'])->getCachedDef();

            if (! is_array($value)) {
                throw new ElementValueInvalidException($value, $name, implode('.', [...$tree, $name]));
            }

            if ($this->hasCardinalityOfOne($definition['cardinality'])) {
                $this->validateRecursive(collect($value), collect($elementDef), [...$tree, $name]);

                return;
            }

            foreach ($value as $val) {
                $this->validateRecursive(collect($val), collect($elementDef), [...$tree, $name]);
            }

            return;
        }

        // Simple value
        if ($this->hasCardinalityOfOne($definition['cardinality'])) {
            if (! $this->validateSimpleValue($definition['type'], $value)) {
                throw new ElementValueInvalidException($value, $name, implode('.', [...$tree, $name]), $definition['type']);
            }

            return;
        }

        foreach ($value as $val) {
            if (! $this->validateSimpleValue($definition['type'], $val)) {
                throw new ElementValueInvalidException($val, $name, implode('.', [...$tree, $name]), $definition['type']);
            }
        }
    }

    private function validateSimpleValue(string $type, mixed $value): bool
    {
        return match ($type) {
            'base64Binary' => is_string($value) && preg_match('/^(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=)?$/', $value),
            'boolean' => $value === true || $value === false,
            'canonical', 'uri', 'url' => is_string($value) && preg_match('/^\S*$/', $value),
            'code' => is_string($value) && preg_match('/^\S+( \S+)*$/', $value),
            'date' => is_string($value) && preg_match('/^([0-9]([0-9]([0-9][1-9]|[1-9]0)|[1-9]00)|[1-9]000)(-(0[1-9]|1[0-2])(-(0[1-9]|[1-2][0-9]|3[0-1]))?)?$/', $value),
            'dateTime' => is_string($value) && preg_match('/^([0-9]([0-9]([0-9][1-9]|[1-9]0)|[1-9]00)|[1-9]000)(-(0[1-9]|1[0-2])(-(0[1-9]|[1-2][0-9]|3[0-1])(T([01][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)(\.[0-9]{1,9})?)?)?(Z|(\+|-)((0[0-9]|1[0-3]):[0-5][0-9]|14:00)?)?)?$/', $value),
            'decimal' => is_numeric($value) && preg_match('/^-?(0|[1-9][0-9]{0,17})(\.[0-9]{1,17})?([eE][+-]?[0-9]{1,9}})?$/', $value),
            'id' => is_string($value) && preg_match('/^[A-Za-z0-9\-.]{1,64}$/', $value),
            'instant' => is_string($value) && preg_match('/^([0-9]([0-9]([0-9][1-9]|[1-9]0)|[1-9]00)|[1-9]000)-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])T([01][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)(\.[0-9]{1,9})?(Z|(\+|-)((0[0-9]|1[0-3]):[0-5][0-9]|14:00))$/', $value),
            'integer', 'integer64' => is_numeric($value) && preg_match('/^([0]|[-+]?[1-9][0-9]*)$/', $value),
            'markdown', 'string' => is_string($value) && preg_match('/^[\s\S]+$/', $value),
            'oid' => is_string($value) && preg_match('/^urn:oid:[0-2](\.(0|[1-9][0-9]*))+$/', $value),
            'positiveInt' => is_numeric($value) && preg_match('/^([1-9][0-9]*)$/', $value),
            'time' => is_string($value) && preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)(\.[0-9]{1,9})?$/', $value),
            'unsignedInt' => is_numeric($value) && preg_match('/^(0|([1-9][0-9]*))$/', $value),
            'uuid' => is_string($value) && preg_match('/^urn:uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $value),
            'xhtml' => $this->validateXHTML($value),
            default => app()->environment('production') ? true : throw new \ErrorException($type),
        };
    }

    public function isElement(string $type): bool
    {
        return str_starts_with($type, 'App\Models\\');
    }

    public function hasCardinalityOfOne(int $cardinality): bool
    {
        return in_array($cardinality, [Base::ONE, Base::ZERO_OR_ONE]);
    }

    private function validateXHTML(mixed $value)
    {
        if (! is_string($value) || ! strlen($value)) {
            return false;
        }
        if (! preg_match('/^<div.+?<\/div>$/s', $value)) {
            return false;
        }

        $value = preg_replace('/>.*?</s', '><', $value);
        $value = preg_replace('/=".*?"/', '=""', $value);
        $value = explode('><', preg_replace('/(^<|>$)/', '', $value));

        try {
            $this->buildTree($value);
        } catch (\Exception $e) {
            return false;
        }

        if (! empty($value)) {
            return false;
        }

        return true;
    }

    private function buildTree(array &$arr): ?array
    {
        if (empty($arr)) {
            return null;
        }

        $tag = array_shift($arr);
        if (str_starts_with($tag, '/')) {
            throw new \Exception('end');
        }

        $tagParts = explode(' ', mb_strtolower($tag));
        $result = ['tag' => $tagParts[0], 'attrs' => [], 'children' => []];

        if (! in_array($result['tag'], ['div', 'p', 'a', 'span', 'b', 'i', 'u', 'ul', 'ol', 'li', 'img', 'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) {
            throw new \Exception('tag');
        }

        for ($i = 1; $i < count($tagParts); $i++) {
            $attr = explode('=', $tagParts[$i])[0];

            if (str_starts_with($attr, 'on')) {
                throw new \Exception('on');
            }

            $result['attrs'][] = $attr;
        }

        if ($result['tag'] === 'a' && ! in_array('name', $result['attrs']) && ! in_array('href', $result['attrs'])) {
            throw new \Exception('a');
        }

        while (count($arr) > 0 && $arr[0] !== '/'.$result['tag']) {
            $sub = $this->buildTree($arr);

            if ($sub === null) {
                throw new \Exception('sub');
            }

            $result['children'][] = $sub;
        }

        if ($arr[0] !== '/'.$result['tag']) {
            throw new \Exception('end');
        }
        array_shift($arr);

        return $result;
    }*/
}
