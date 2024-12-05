<?php

namespace App\Http\Controllers\Api\App;

use App\Constants\LoincCode;
use App\Constants\System;
use App\Fhir\Base\Resource\DomainResource\Device;
use App\Fhir\Base\Resource\DomainResource\Observation;
use App\Fhir\Base\Resource\DomainResource\Patient;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHealthDataRequest;
use Illuminate\Support\Carbon;

class ShareHealthDataController extends Controller
{
    public function store(StoreHealthDataRequest $request)
    {
        $patient = $request->user()->patient();

        $deviceProps = $request->get('device');
        $payload = $request->get('payload');
        $device = $this->getOrCreateDevice($deviceProps);

        foreach ($payload as $date => $datePayload) {
            $issued = (new Carbon($date))->startOfDay()->format('Y-m-d\TH:i:s');

            // Delete previous observation for the same date
            Observation::whereReference('subject', $patient)
                ->whereNull('resource->derivedFrom')
                ->where('resource->issued', $issued)
                ->delete();

            foreach ($datePayload as $type => $data) {
                switch ($type) {
                    case 'breathingRate':
                    case 'calories':
                    case 'distance':
                    case 'elevation':
                    case 'exercise':
                    case 'heartRate':
                    case 'hrv':
                    case 'sleep':
                    case 'steps':
                    case 'spO2':
                    case 'vo2max':
                        break;
                    default:
                        continue 2;
                }

                foreach ($data as $dataPoint) {
                    $fn = "create_{$type}_observation";
                    $this->{$fn}($patient, $device, $issued, $dataPoint);
                }
            }
        }
    }

    private function getOrCreateDevice(mixed $deviceProps): ?Device
    {
        $device = Device::where('resource->serialNumber', $deviceProps['serialNumber'])
            ->where('resource->modelNumber', $deviceProps['model'])
            ->where('resource->name[*]->value', 'like', "%\"{$deviceProps['name']}\"%")
            ->first();

        if (is_null($device)) {
            $device = Device::create([
                'serialNumber' => $deviceProps['serialNumber'],
                'name' => [
                    [
                        'value' => $deviceProps['name'],
                        'type' => 'registered-name',
                    ],
                ],
                'modelNumber' => $deviceProps['model'],
            ]);
        }

        return $device;
    }

    private function create_vo2max_observation(Patient $patient, Device $device, string $issued, array $dataPoint)
    {
        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => LoincCode::Vo2Max,
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $dataPoint['dateTime'],
            'issued' => $issued,
            'valueRange' => [
                'low' => [
                    'value' => $dataPoint['min'],
                    'unit' => 'mL/kg/min',
                    'system' => System::Ucum,
                    'code' => 'mL/kg/min',
                ],
                'high' => [
                    'value' => $dataPoint['max'],
                    'unit' => 'mL/kg/min',
                    'system' => System::Ucum,
                    'code' => 'mL/kg/min',
                ],
            ],
            'device' => [
                'reference' => "Device/$device->_id",
            ],
        ]);
    }

    private function create_spO2_observation(Patient $patient, Device $device, string $issued, array $dataPoint)
    {
        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => LoincCode::SpO2,
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $dataPoint['dateTime'],
            'issued' => $issued,
            'valueQuantity' => [
                'value' => $dataPoint['value'],
                'unit' => '%',
                'system' => System::Ucum,
                'code' => '%',
            ],
            'device' => [
                'reference' => "Device/$device->_id",
            ],
        ]);
    }

    private function create_sleep_observation(Patient $patient, Device $device, string $issued, array $dataPoint)
    {
        $levelMap = [
            'rem' => LoincCode::SleepREM,
            'light' => LoincCode::SleepLight,
            'deep' => LoincCode::SleepDeep,
        ];

        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => $levelMap[$dataPoint['level']],
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $dataPoint['dateTime'],
            'issued' => $issued,
            'valueQuantity' => [
                'value' => $dataPoint['seconds'],
                'unit' => 's',
                'system' => System::Ucum,
                'code' => 's',
            ],
            'device' => [
                'reference' => "Device/$device->_id",
            ],
        ]);
    }

    private function create_hrv_observation(Patient $patient, Device $device, string $issued, array $dataPoint)
    {
        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => LoincCode::HeartRateVariability,
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $dataPoint['dateTime'],
            'issued' => $issued,
            'valueQuantity' => [
                'value' => $dataPoint['value'],
                'unit' => 'ms',
                'system' => System::Ucum,
                'code' => 'ms',
            ],
            'device' => [
                'reference' => "Device/$device->_id",
            ],
        ]);
    }

    private function create_exercise_observation(Patient $patient, Device $device, string $issued, array $dataPoint)
    {
        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => LoincCode::Exercise,
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $dataPoint['dateTime'],
            'issued' => $issued,
            'valueQuantity' => [
                'value' => $dataPoint['value'],
                'unit' => 'min',
                'system' => System::Ucum,
                'code' => 'min',
            ],
            'device' => [
                'reference' => "Device/$device->_id",
            ],
        ]);
    }

    private function create_elevation_observation(Patient $patient, Device $device, string $issued, array $dataPoint)
    {
        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => LoincCode::Elevation,
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $dataPoint['dateTime'],
            'issued' => $issued,
            'valueQuantity' => [
                'value' => $dataPoint['value'],
                'unit' => 'm/hour',
                'system' => System::Ucum,
                'code' => '/h',
            ],
            'device' => [
                'reference' => "Device/$device->_id",
            ],
        ]);
    }

    private function create_distance_observation(Patient $patient, Device $device, string $issued, array $dataPoint)
    {
        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => LoincCode::Distance,
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $dataPoint['dateTime'],
            'issued' => $issued,
            'valueQuantity' => [
                'value' => $dataPoint['value'],
                'unit' => 'km/hour',
                'system' => System::Ucum,
                'code' => '/h',
            ],
            'device' => [
                'reference' => "Device/$device->_id",
            ],
        ]);
    }

    private function create_calories_observation(Patient $patient, Device $device, string $issued, array $dataPoint)
    {
        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => LoincCode::Calories,
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $dataPoint['dateTime'],
            'issued' => $issued,
            'valueQuantity' => [
                'value' => $dataPoint['value'],
                'unit' => 'kcal/hour',
                'system' => System::Ucum,
                'code' => 'kcal/h',
            ],
            'device' => [
                'reference' => "Device/$device->_id",
            ],
        ]);
    }

    private function create_breathingRate_observation(Patient $patient, Device $device, string $issued, array $dataPoint)
    {
        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => LoincCode::BreathingRate,
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $dataPoint['dateTime'],
            'issued' => $issued,
            'valueQuantity' => [
                'value' => $dataPoint['value'],
                'unit' => 'breaths/minute',
                'system' => System::Ucum,
                'code' => '/min',
            ],
            'device' => [
                'reference' => "Device/$device->_id",
            ],
        ]);
    }

    private function create_steps_observation(Patient $patient, Device $device, string $issued, array $dataPoint)
    {
        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => LoincCode::Steps,
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $dataPoint['dateTime'],
            'issued' => $issued,
            'valueQuantity' => [
                'value' => $dataPoint['value'],
                'unit' => 'steps/hour',
                'system' => System::Ucum,
                'code' => '/h',
            ],
            'device' => [
                'reference' => "Device/$device->_id",
            ],
        ]);
    }

    private function create_heartRate_observation(Patient $patient, Device $device, string $issued, array $dataPoint)
    {
        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => LoincCode::HeartRate,
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $dataPoint['dateTime'],
            'issued' => $issued,
            'valueRange' => [
                'low' => [
                    'value' => $dataPoint['min'],
                    'unit' => 'beats/minute',
                    'system' => System::Ucum,
                    'code' => '/min',
                ],
                'high' => [
                    'value' => $dataPoint['max'],
                    'unit' => 'beats/minute',
                    'system' => System::Ucum,
                    'code' => '/min',
                ],
            ],
            'device' => [
                'reference' => "Device/$device->_id",
            ],
        ]);
    }
}
