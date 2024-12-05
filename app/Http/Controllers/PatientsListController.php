<?php

namespace App\Http\Controllers;

use App\Queries\PatientListQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientsListController extends Controller
{
    public function __invoke(Request $request, PatientListQuery $patientListQuery)
    {
        return Inertia::render('PatientList', [
            'patients' => $patientListQuery(),
        ]);
    }
}
