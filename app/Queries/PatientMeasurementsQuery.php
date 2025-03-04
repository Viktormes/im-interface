<?php

namespace App\Queries;

use App\Constants\LoincCode;
use App\Constants\System;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PatientMeasurementsQuery
{
    public function __invoke(string $patientId, Carbon $from, Carbon $to): array
    {
        $f = $from->format('Y-m-d');
        $t = $to->format('Y-m-d');

        $mappedObservations = [];

        $this->getRegularObservations($mappedObservations, $patientId, $f, $t);

        return $mappedObservations;
    }

    private function getRegularObservations(array &$mappedObservations, string $patientId, string $f, string $t)
    {
        $system = System::Loinc;
        $loincCodes = implode(',', LoincCode::supportedMeasurements());

        //TODO: Maybe?
        /*
        FhirApi::resource('Observation')
            ->where('code', "$system|")
            ->where('code', $loincCodes)
            ->where('patient', $patientId)
            ->where('date', "ge$f")
            ->where('date', "le$t")
            ->sort('lastUpdated')
            ->elements('code,effective,value')
            ->search();
        */

        $observations = Http::get(env('APP_URL') . "/api/fhir/Observation?code=$system|&code=$loincCodes&patient=$patientId&_sort=lastUpdated&date=ge$f&date=le$t&_elements=code,effective,value");

        if (is_null($total = $observations->json('total')) || $total <= 0) {
            return;
        }

        foreach ($observations->json('entry') as ['resource' => $resource]) {
            $loincCode = data_get(collect($resource['code']['coding'])->where('system', $system), '0.code');

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
            $day = $date->format('Y-m-d');
            $time = $date->format('H:i:s');
            $mappedObservations[$loincCode][$day][] = [
                'time' => $time,
                'value' => $value,
            ];
        }
    }
}
