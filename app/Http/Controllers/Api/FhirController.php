<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BadRequestException;
use App\Exceptions\DeletedException;
use App\Exceptions\InvalidContentException;
use App\Exceptions\InvalidHeaderException;
use App\Exceptions\MultipleMatchesException;
use App\Exceptions\NotFoundException;
use App\Exceptions\NotSupportedException;
use App\Exceptions\VersionMismatchException;
use App\Fhir\Base\Element\BackboneElement\BundleEntry;
use App\Fhir\Base\Element\BackboneElement\BundleEntryRequest;
use App\Fhir\Base\Element\BackboneElement\BundleEntryResponse;
use App\Fhir\Base\Element\BackboneElement\BundleEntrySearch;
use App\Fhir\Base\Element\BackboneElement\BundleLink;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInstant;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUnsignedInt;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Base\Resource;
use App\Fhir\Base\Resource\Bundle;
use App\Fhir\Base\Resource\DomainResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\FhirCreateRequest;
use App\Http\Requests\FhirReadRequest;
use App\Services\FhirService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class FhirController extends Controller
{
    public function create(FhirCreateRequest $request, FhirService $service, string $resourceType): Response|JsonResponse
    {
        $payload = $service->prepareCreatePayload($request->json()->all(), $resourceType);
        $resourceClass = $service->validateDomainResource($payload);

        if (! collect(data_get($payload, 'meta.tag'))->where('code', 'SUBSETTED')->isEmpty()) {
            throw new BadRequestException('Unable to perform create with SUBSETTED data');
        }

        $returnType = $request->header('Prefer', 'representation');
        if (! in_array($returnType, ['minimal', 'representation', 'OperationOutcome'])) {
            throw new InvalidHeaderException("Invalid value '$returnType' for header 'Prefer'");
        }

        $resource = null;

        if ($ifNoneExist = $request->header('If-None-Exist')) {
            parse_str($ifNoneExist, $params);
            $count = $resourceClass::searchCount($params);

            if ($count > 1) {
                throw new MultipleMatchesException;
            }

            if ($count == 1) {
                $result = $resourceClass::search($params);
                $resource = $result->items[0];
            }
        }

        if (is_null($resource)) {
            $resource = $resourceClass::create($payload);
        }

        return match ($returnType) {
            'representation' => $this->resourceResponse($resource, 201, [
                'Location' => url("/api/fhir/$resourceType/$resource->_id/_history/$resource->_versionId"),
            ]),
            'OperationOutcome' => $this->operationOutcomeResponse($resource, 'information', 'informational', 'Resource created', 201),
            default => $this->emptyResponse($resource, 201),
        };
    }

    public function search(Request $request, FhirService $service, ?string $type = null): JsonResponse
    {
        if (is_null($type)) {
            throw new NotSupportedException('Operation not yet supported', 501);
        }

        $query = explode('&', $_SERVER['QUERY_STRING']);
        $params = [];

        foreach ($query as $param) {
            if (! str_contains($param, '=')) {
                $param .= '=';
            }

            [$name, $value] = explode('=', $param, 2);
            $params[urldecode($name)][] = urldecode($value);
        }
        $searchParams = collect($params)->mapWithKeys(function ($value, $key) {
            if (count($value) === 1) {
                return [$key => $value[0]];
            }

            return [$key => $value];
        })->toArray();

        $resourceClass = $service->resolveResourceClass($type);

        $searchResult = $resourceClass::search($searchParams);
        $included = collect();

        if ($revInclude = $request->get('_revinclude')) {
            foreach (explode(',', $revInclude) as $inc) {
                [$revIncType, $revIncField] = explode(':', $inc);

                $revIncResourceClass = $service->resolveResourceClass($revIncType);

                $in = $searchResult->items->pluck('id.value')->map(fn ($id) => "$type/$id");
                $revIncRes = $revIncResourceClass::query()->whereIn("resource->{$revIncField}->reference", $in)->get();

                $included = $included->concat($revIncRes);
            }
        }

        if ($include = $request->get('_include')) {
            foreach (explode(',', $include) as $inc) {
                [$revIncType, $revIncField] = explode(':', $inc);

                $revIncResourceClass = $service->resolveResourceClass($revIncType);

                $in = $searchResult->items->pluck("$revIncField.reference.value")->map(fn ($ref) => explode('/', $ref)[1]);
                $revIncRes = $revIncResourceClass::query()->whereIn('id', $in)->get();

                $included = $included->concat($revIncRes);
            }
        }

        // TODO: Add support for _count and pagination

        $bundle = new Bundle;
        $bundle->type = new FhirCode('searchset');
        $bundle->timestamp = new FhirInstant(now());
        $bundle->total = new FhirUnsignedInt($searchResult->total);
        $bundle->add('link', (function () use ($request) {
            $link = new BundleLink;
            $link->relation = new FhirCode('self');
            $link->url = new FhirUri($request->url().'?'.$_SERVER['QUERY_STRING']);

            return $link;
        })());

        foreach ($searchResult->items as $item) {
            $entry = new BundleEntry;
            $type = class_basename($item);
            $entry->fullUrl = new FhirUri(url("/api/fhir/$type/{$item->id->value}"));
            $entry->resource = $item;
            $entry->search = new BundleEntrySearch;
            $entry->search->mode = new FhirCode('match');

            $bundle->add('entry', $entry);
        }

        foreach ($included as $item) {
            $entry = new BundleEntry;
            $type = class_basename($item);
            $entry->fullUrl = new FhirUri(url("/api/fhir/$type/{$item->id->value}"));
            $entry->resource = $item;
            $entry->search = new BundleEntrySearch;
            $entry->search->mode = new FhirCode('include');

            $bundle->add('entry', $entry);
        }

        return $this->resourceResponse($bundle);
    }

    private function resourceResponse(Resource $resource, int $statusCode = 200, array $extraHeaders = [])
    {
        $arrayedResource = [];

        if (request()->query('_summary') === 'true') {
            $arrayedResource = $resource->serialize(summary: true);
        } elseif ($elements = request()->query('_elements')) {
            $elements = explode(',', $elements);
            $arrayedResource = $resource->serialize(elements: $elements);
        } else {
            $arrayedResource = $resource->serialize();
        }

        return response()->json(
            $arrayedResource,
            $statusCode,
            array_merge($this->buildResponseHeaders($resource), $extraHeaders),
            request()->query('_pretty') === 'true' ? JSON_PRETTY_PRINT : 0,
        );
    }

    private function buildResponseHeaders(Resource $resource): array
    {
        $headers = [
            'Content-Type' => 'application/fhir+json',
            'Date' => now()->format('D, d M Y H:i:s \G\M\T'),
        ];

        if (is_subclass_of($resource, DomainResource::class)) {
            $versionId = $resource->_versionId;
            $headers['ETag'] = "W/\"$versionId\"";

            if ($resource->_resourceStatus !== 'deleted') {
                $lastUpdated = (new Carbon($resource->_lastUpdated))->format('D, d M Y H:i:s \G\M\T');
                $headers['Last-Modified'] = $lastUpdated;
            }
        }

        return $headers;
    }

    private function operationOutcomeResponse(DomainResource $resource, string $severity, string $code, string $text, int $statusCode = 200, array $extraHeaders = [])
    {
        return response()->json(
            [
                'resourceType' => 'OperationOutcome',
                'issue' => [
                    [
                        'severity' => $severity,
                        'code' => $code,
                        'details' => [
                            'text' => $text,
                        ],
                    ],
                ],
            ],
            $statusCode,
            array_merge($this->buildResponseHeaders($resource), $extraHeaders),
            request()->query('_pretty') === 'true' ? JSON_PRETTY_PRINT : 0,
        );
    }

    private function emptyResponse(DomainResource $resource, int $statusCode = 200, array $extraHeaders = [])
    {
        return response(
            null,
            $statusCode,
            array_merge($this->buildResponseHeaders($resource), $extraHeaders),
        );
    }

    public function read(FhirReadRequest $request, FhirService $service, string $type, string $id): JsonResponse
    {
        //TODO: Add support for conditional reads,   If-Modified-Since   and   If-None-Match
        //  * If-None-Match - Respond with 304 Not Modified if no records match provided ETags
        //  * If-Modified-Since - if not modified since provided date-time, respond with 304 Not Modified
        $resourceClass = $service->resolveResourceClass($type);

        $resource = $resourceClass::query()->where('id', $id)->first();

        if (is_null($resource)) {
            throw new NotFoundException("$type with id '$id' not found");
        }

        if ($resource->_resourceStatus === 'deleted') {
            throw new DeletedException('This resource has been deleted');
        }

        return $this->resourceResponse($resource);
    }

    public function vread(Request $request, FhirService $service, string $type, string $id, string $vid): JsonResponse
    {
        $resourceClass = $service->resolveResourceClass($type);

        $resource = $resourceClass::query()->where('id', $id)->where('version_id', $vid)->first();

        if (! is_null($resource)) {
            if ($resource->_resourceStatus === 'deleted') {
                throw new DeletedException('This resource has been deleted');
            }

            return $this->resourceResponse($resource);
        }

        $resource = $resourceClass::queryHistory()->where('id', $id)->where('version_id', $vid)->first();

        if (is_null($resource)) {
            throw new NotFoundException("$type with id '$id|$vid' not found");
        }

        if ($resource->_resourceStatus === 'deleted') {
            throw new DeletedException('This resource has been deleted');
        }

        return $this->resourceResponse($resource);
    }

    public function update(Request $request, FhirService $service, string $type, ?string $id = null): Response|JsonResponse
    {
        if (is_null($id)) {
            throw new NotSupportedException('Operation not yet supported (Conditional update)', 501);
        }

        $payload = $service->prepareUpdatePayload($request->json()->all(), $type);
        $resourceClass = $service->validateDomainResource($payload);

        if (! collect(data_get($payload, 'meta.tag'))->where('code', 'SUBSETTED')->isEmpty()) {
            throw new BadRequestException('Unable to perform update with SUBSETTED data');
        }

        if (! array_key_exists('id', $payload)) {
            throw new BadRequestException("Missing element 'id' in resource");
        }

        if ($id !== $payload['id']) {
            throw new BadRequestException("Mismatch of element 'id' in resource");
        }

        $matchVersion = null;
        if ($ifMatch = $request->header('If-Match')) {
            if (! preg_match('/^W\/"(\d+)"$/', $ifMatch, $match)) {
                throw new InvalidHeaderException("Invalid value '$ifMatch' for header 'If-Match'");
            }

            $matchVersion = $match[1];
        }

        $returnType = $request->header('Prefer', 'representation');
        if (! in_array($returnType, ['minimal', 'representation', 'OperationOutcome'])) {
            throw new InvalidHeaderException("Invalid value '$returnType' for header 'Prefer'");
        }

        $resource = $resourceClass::query()->where('id', $id)->first();

        if (is_null($resource)) {
            throw new NotFoundException("$type with id '$id' not found");
        }

        if (! is_null($matchVersion) && $matchVersion !== $resource->_versionId) {
            throw new VersionMismatchException;
        }

        $resource->update($payload);

        return match ($returnType) {
            'representation' => $this->resourceResponse($resource, 200),
            'OperationOutcome' => $this->operationOutcomeResponse($resource, 'information', 'informational', 'Resource updated', 200),
            default => $this->emptyResponse($resource, 200),
        };
    }

    public function patch(Request $request, string $type, string $id): JsonResponse
    {
        throw new NotSupportedException('Operation not yet supported (patch)', 501);
    }

    public function delete(Request $request, FhirService $service, ?string $type = null, ?string $id = null): Response
    {
        if (is_null($type) || is_null($id)) {
            throw new NotSupportedException('Operation not yet supported (Conditional delete)', 501);
        }

        if (! empty($request->json()->all())) {
            throw new InvalidContentException('Request body must be empty');
        }

        $resourceClass = $service->resolveResourceClass($type);
        $resource = $resourceClass::query()->where('id', $id)->first();

        if (is_null($resource)) {
            throw new NotFoundException("$type with id '$id' not found");
        }

        $resource->delete();

        return $this->emptyResponse($resource, 200);
    }

    public function history(Request $request, FhirService $service, ?string $type = null, ?string $id = null): JsonResponse
    {
        if (is_null($type) || is_null($id)) {
            throw new NotSupportedException('Operation not yet supported', 501);
        }

        /** @var class-string<DomainResource> $resourceClass */
        $resourceClass = $service->resolveResourceClass($type);
        $resource = $resourceClass::query()->where('id', $id)->first();

        if (is_null($resource)) {
            throw new NotFoundException("$type with id '$id' not found");
        }

        $entries = $resourceClass::queryHistory()->where('id', $id)->orderByDesc('version_id')->get();
        $entries->prepend($resource);

        $bundle = new Bundle;
        $bundle->type = new FhirCode('history');
        $bundle->timestamp = new FhirInstant(now());
        $bundle->add('link', (function () use ($request) {
            $link = new BundleLink;
            $link->relation = new FhirCode('self');
            $link->url = new FhirUri($request->fullUrl());

            return $link;
        })());

        foreach ($entries as $item) {
            $entry = new BundleEntry;
            $type = class_basename($item);
            $entry->fullUrl = new FhirUri(url("/api/fhir/$type/{$item->_id}/_history/$item->_versionId"));

            switch ($item->_resourceStatus) {
                case 'created':
                    $entry->resource = $item;
                    $entry->request = new BundleEntryRequest;
                    $entry->request->method = new FhirCode('POST');
                    $entry->request->url = new FhirUri(url("/api/fhir/$type"));
                    $entry->response = new BundleEntryResponse;
                    $entry->response->status = new FhirString('201');
                    $entry->response->location = new FhirUri(url("/api/fhir/$type/$item->_id"));
                    $entry->response->etag = new FhirString("W/\"$item->_versionId\"");
                    $entry->response->lastModified = new FhirInstant($item->_lastUpdated);
                    break;
                case 'updated':
                case 'restored':
                    $entry->resource = $item;
                    $entry->request = new BundleEntryRequest;
                    $entry->request->method = new FhirCode('PUT');
                    $entry->request->url = new FhirUri(url("/api/fhir/$type/$item->_id"));
                    $entry->response = new BundleEntryResponse;
                    $entry->response->status = new FhirString('200');
                    $entry->response->etag = new FhirString("W/\"$item->_versionId\"");
                    $entry->response->lastModified = new FhirInstant($item->_lastUpdated);
                    break;
                case 'deleted':
                    $entry->request = new BundleEntryRequest;
                    $entry->request->method = new FhirCode('DELETE');
                    $entry->request->url = new FhirUri(url("/api/fhir/$type/$item->_id"));
                    $entry->response = new BundleEntryResponse;
                    $entry->response->status = new FhirString('200');
                    $entry->response->etag = new FhirString("W/\"$item->_versionId\"");
                    $entry->response->lastModified = new FhirInstant($item->_lastUpdated);
                    break;
            }

            $bundle->add('entry', $entry);
        }

        return $this->resourceResponse($bundle);
    }

    public function compartmentSearch(Request $request, ?string $compartmentType = null, ?string $compartmentId = null, ?string $type = null): JsonResponse
    {
        throw new NotSupportedException('Operation not yet supported (compartment search)', 501);
        dd('compartmentSearch', $compartmentType, $compartmentId, $type, $request->all());
    }

    public function capabilities(Request $request): JsonResponse
    {
        throw new NotSupportedException('Operation not yet supported (capabilities)', 501);
        dd('capabilities', $request->all());
    }

    public function batch(Request $request, FhirService $service): JsonResponse
    {
        $bundle = Bundle::make($request->all());

        $resultBundle = Bundle::make(['type' => 'batch-response']);

        foreach ($bundle->entry as $entry) {
            $method = strtolower(data_get($entry, 'request.method.value'));
            $url = data_get($entry, 'request.url.value');

            $response = Http::{$method}(url('api/fhir'.$url));
            $resourceType = data_get($response->json(), 'resourceType');

            $entry = new BundleEntry;
            $resultBundle->add('entry', $entry);

            if ($resourceType === 'Bundle') {
                $entry->resource = Bundle::make($response->json());
            } else {
                $c = $service->resolveResourceClass($resourceType);
                $entry->resource = $c::make($response->json());
            }

            $entry->response = new BundleEntryResponse;
            $entry->response->status = new FhirString($response->status());

            if ($etag = $response->header('ETag')) {
                preg_match('/^W\/\"(\d+)\"$/', $etag, $m);
                $entry->response->etag = new FhirString("W/$m[1]");
            }

            if ($lastModified = $response->header('Last-Modified')) {
                $entry->response->lastModified = new FhirInstant(Carbon::parse($lastModified)->toIso8601String());
            }
        }

        // throw new NotSupportedException('Operation not yet supported (batch)', 501);
        // dd('batch', $resultBundle);

        return $this->resourceResponse($resultBundle);
    }
}
