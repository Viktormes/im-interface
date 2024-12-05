<?php

namespace App\Http\Controllers;

use App\Queries\PatientListQuery;
use App\Sessions\SelectedPatientSession;
use Illuminate\Validation\ValidationException;

class SelectedPatientController extends Controller
{
    public function store(
        SelectedPatientSession $selectedPatient,
        PatientListQuery $patientListQuery,
        string $patient
    ) {
        if (is_null($p = $patientListQuery()->keyBy('id')->get($patient))) {
            throw ValidationException::withMessages([
                'patient' => 'The selected patient does not exist.',
            ]);
        }

        $selectedPatient->set($p);

        return redirect()->to('/dashboard');
    }

    public function destroy(SelectedPatientSession $selectedPatient)
    {
        $selectedPatient->clear();

        return redirect()->to('/');
    }
}
