<?php

namespace App\Queries;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PatientListQuery
{
    private const CACHE_KEY = 'patients-list';

    public function __invoke(): Collection
    {
        //TODO: cache per organization
        return Cache::remember(static::CACHE_KEY, 60, function () {
            [$patients, $conditions] = $this->getPatientsWithConditions();

            $observations = $this->getObservations($patients);

            return $patients->map(function ($patient) use ($conditions, $observations) {
                $p = collect($patient);

                return [
                    ...$p->only('id', 'identifier', 'name', 'generalPractitioner'),
                    'diagnoses' => $conditions[$p->get('id')],
                    'last_observation' => data_get($observations, $p->get('id')),
                ];
            });
        });
    }

    private function getPatientsWithConditions(): array
    {
        $patientsResponse = Http::get(url('/api/fhir/Patient?_revinclude=Condition:subject'));
        $grouped = collect($patientsResponse->json('entry'))->pluck('resource')->groupBy('resourceType');

        $keyedConditions = [];
        $grouped['Condition']->each(function ($condition) use (&$keyedConditions) {
            $ref = data_get($condition, 'subject.reference');
            $pid = collect(explode('/', $ref))->last();

            $keyedConditions[$pid][] = data_get($condition, 'code');
        });

        return [$grouped['Patient'], $keyedConditions];
    }

    private function getObservations($patients): array
    {
        $observationsResponse = Http::post(url('/api/fhir/'), [
            'resourceType' => 'Bundle',
            'type' => 'batch',
            'entry' => $patients->pluck('id')->map(function ($pid) {
                return [
                    'request' => [
                        'method' => 'GET',
                        'url' => "/Observation?patient=$pid&_sort=-lastUpdated&_maxresults=1",
                    ],
                ];
            }),
        ]);

        $keyedObservations = [];
        collect($observationsResponse['entry'])->each(function ($observation) use (&$keyedObservations) {
            if (! (int) data_get($observation, 'resource.total')) {
                return;
            }

            $resource = data_get($observation, 'resource.entry.0.resource');
            $ref = data_get($resource, 'subject.reference');
            $pid = collect(explode('/', $ref))->last();

            $keyedObservations[$pid] = Carbon::parse(data_get($resource, 'meta.lastUpdated'))?->toDateTimeLocalString();
        });

        return $keyedObservations;
    }
}
