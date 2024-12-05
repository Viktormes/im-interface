<?php

namespace App\Queries;

use App\Constants\LoincCode;
use App\Constants\System;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PatientReportedOutcomesQuery
{
    public function __invoke(string $patientId, Carbon $from, Carbon $to): array
    {
        $f = $from->format('Y-m-d');
        $t = $to->format('Y-m-d');

        return $this->getQuestionnaireObservations($patientId, $f, $t);
    }

    private function getQuestionnaireObservations(string $patientId, string $f, string $t): array
    {
        $system = System::Loinc;
        $loincCode = LoincCode::PatientReportedOutcomeMeasureScore;

        $observations = Http::get(url("/api/fhir/Observation?code=$system|&code=$loincCode&patient=$patientId&_sort=lastUpdated&date=ge$f&date=le$t&_elements=code,effective,value"));

        if (is_null($total = $observations->json('total')) || $total <= 0) {
            return [];
        }

        $mappedObservations = [];

        foreach ($observations->json('entry') as ['resource' => $resource]) {
            $questionnaire = data_get($resource['code'], 'text');

            $value = null;

            if (! is_null($valueQuantity = data_get($resource, 'valueQuantity'))) {
                $value = $valueQuantity['value'];
            } elseif (! is_null($valueRange = data_get($resource, 'valueRange'))) {
                $value = [
                    $valueRange['low']['value'],
                    $valueRange['high']['value'],
                ];
            }

            $date = Carbon::parse($resource['effectiveDateTime']);
            $mappedObservations[] = [
                'label' => $questionnaire,
                'dateTime' => $date->format('Y-m-d\TH:i:s'),
                'format' => '{0:N1}',
                'value' => $value,
                'referenceRange' => 4,
            ];
        }

        return $mappedObservations;
    }
}
