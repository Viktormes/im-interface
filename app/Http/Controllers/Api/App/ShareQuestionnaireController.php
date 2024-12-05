<?php

namespace App\Http\Controllers\Api\App;

use App\Constants\LoincCode;
use App\Constants\System;
use App\Fhir\Base\Resource\DomainResource\Device;
use App\Fhir\Base\Resource\DomainResource\Observation;
use App\Fhir\Base\Resource\DomainResource\Patient;
use App\Fhir\Base\Resource\DomainResource\Questionnaire;
use App\Fhir\Base\Resource\DomainResource\QuestionnaireResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ShareQuestionnaireController extends Controller
{
    public function basdai(Request $request)
    {
        $request->validate([
            'answers' => ['required', 'array'],
            'answers.1\.1' => ['required', 'numeric', 'min:0', 'max:10'],
            'answers.1\.2' => ['required', 'numeric', 'min:0', 'max:10'],
            'answers.1\.3' => ['required', 'numeric', 'min:0', 'max:10'],
            'answers.1\.4' => ['required', 'numeric', 'min:0', 'max:10'],
            'answers.1\.5' => ['required', 'numeric', 'min:0', 'max:10'],
            'answers.1\.6' => ['required', 'numeric', 'min:0', 'max:10'],
            'answers.2\.1' => ['sometimes', 'nullable', 'string', 'max:200'],
        ]);

        //TODO: Make this more secure
        $questionnaire = Questionnaire::query()->where('resource->title', 'like', '%BASDAI%')->first();
        $patient = $request->user()->patient();
        $device = $this->getOrCreateDevice($request->get('device'));

        $a = $request->answers;
        $totalBasdaiScore = ($a['1.1'] + $a['1.2'] + $a['1.3'] + $a['1.4'] + ($a['1.5'] + $a['1.6']) / 2) / 5;

        $questionnaireResponse = $this->createQuestionnaireResponse($patient, $questionnaire, [
            [
                'linkId' => '1',
                'item' => [
                    [
                        'linkId' => '1.1',
                        'answer' => [[
                            'valueDecimal' => $a['1.1'],
                        ]],
                    ],
                    [
                        'linkId' => '1.2',
                        'answer' => [[
                            'valueDecimal' => $a['1.2'],
                        ]],
                    ],
                    [
                        'linkId' => '1.3',
                        'answer' => [[
                            'valueDecimal' => $a['1.3'],
                        ]],
                    ],
                    [
                        'linkId' => '1.4',
                        'answer' => [[
                            'valueDecimal' => $a['1.4'],
                        ]],
                    ],
                    [
                        'linkId' => '1.5',
                        'answer' => [[
                            'valueDecimal' => $a['1.5'],
                        ]],
                    ],
                    [
                        'linkId' => '1.6',
                        'answer' => [[
                            'valueDecimal' => $a['1.6'],
                        ]],
                    ],
                ],
            ],
            [
                'linkId' => '2',
                'item' => [
                    [
                        'linkId' => '2.1',
                        'answer' => [[
                            'valueString' => $a['2.1'] ?? '',
                        ]],
                    ],
                ],
            ],
        ]);

        $this->createObservation($patient, $device, $questionnaireResponse, [
            'value' => $totalBasdaiScore,
            'unit' => '{score}',
            'system' => System::Ucum,
            'code' => '{score}',
        ]);
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

    private function createQuestionnaireResponse(Patient $patient, Questionnaire $questionnaire, array $item): QuestionnaireResponse
    {
        $authored = (new Carbon)->format('Y-m-d\TH:i:s');

        return QuestionnaireResponse::create([
            'questionnaire' => url('/api/fhir/Questionnaire/'.$questionnaire->_id),
            'status' => 'completed',
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'authored' => $authored,
            'source' => [
                'reference' => "Device/$patient->_id",
            ],
            'item' => $item,
        ]);
    }

    private function createObservation(Patient $patient, Device $device, QuestionnaireResponse $questionnaireResponse, array $value): void
    {
        $effective = (new Carbon)->format('Y-m-d\TH:i:s');
        $issued = (new Carbon)->startOfDay()->format('Y-m-d\TH:i:s');

        Observation::create([
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => System::Loinc,
                        'code' => LoincCode::PatientReportedOutcomeMeasureScore,
                    ],
                ],
                'text' => 'BASDAI',
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'effectiveDateTime' => $effective,
            'issued' => $issued,
            'valueQuantity' => $value,
            'device' => [
                'reference' => "Device/$device->_id",
            ],
            'derivedFrom' => [
                [
                    'reference' => "QuestionnaireResponse/$questionnaireResponse->_id",
                ],
            ],
        ]);
    }
}
