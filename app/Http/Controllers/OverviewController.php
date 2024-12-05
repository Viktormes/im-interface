<?php

namespace App\Http\Controllers;

use App\Concerns\HasPeriodWithAccuracy;
use App\Queries\PatientMeasurementsQuery;
use App\Queries\PatientReportedOutcomesQuery;
use App\Services\MeasurementsService;
use App\Sessions\SelectedPatientSession;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OverviewController extends Controller
{
    use HasPeriodWithAccuracy;

    public function index(
        Request $request,
        SelectedPatientSession $selectedPatient,
        PatientMeasurementsQuery $measurementsQuery,
        PatientReportedOutcomesQuery $promsQuery,
        MeasurementsService $service
    ): Response {
        [$from, $to, $accuracy] = $this->resolvePeriodAndAccuracy($request);

        return Inertia::render('Overview', [
            'period' => [
                'accuracy' => $accuracy,
                'start' => $from->toDateTimeLocalString(),
                'end' => $to->toDateTimeLocalString(),
            ],
            'measurements' => $service->group($measurementsQuery($selectedPatient->id, $from, $to)),
            'proms' => $promsQuery($selectedPatient->id, $from, $to),
        ]);
    }
}
