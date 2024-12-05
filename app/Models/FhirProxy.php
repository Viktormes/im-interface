<?php

namespace App\Models;

use App\Fhir\Base\Resource\DomainResource;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Proxy class that enables querying of Fhir Domain Resources
 *
 * Example usage:
 * Patient::query()->where('id', $id)->first();
 */
class FhirProxy extends Model
{
    public $timestamps = false;

    public function hydrate(array $items)
    {
        return $this->newCollection(array_map(function ($item) {
            $resourceData = json_decode($item->resource, true);
            if (empty($resourceData)) {
                return null;
            }

            $resourceType = $resourceData['resourceType'];
            unset($resourceData['resourceType']);

            $resourceClass = "App\\Fhir\\Base\\Resource\\DomainResource\\$resourceType";

            $resource = $resourceClass::make($resourceData);

            $resource->_id = $item->id;
            $resource->_resourceStatus = $item->status;
            $resource->_lastUpdated = new Carbon($item->last_updated);
            $resource->_versionId = $item->version_id;

            return $resource;
        }, $items));
    }

    /*
     * QUERY HELPERS (Scopes)
     */
    public function scopeWhereReference(Builder $builder, string $element, DomainResource|string $referenced, ?string $id = null): Builder
    {
        $type = $referenced;

        if ($referenced instanceof DomainResource) {
            $type = class_basename($referenced);
            $id = $referenced->_id;
        }

        return $builder->where("resource->{$element}->reference", "$type/$id");
    }

    public function scopeWhereDateBetween(Builder $builder, string $element, Carbon $start, Carbon $end): Builder
    {
        return $builder->whereDate("resource->{$element}", '>=', $start)
            ->whereDate("resource->{$element}", '<=', $end);
    }
}
