<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class TreatmentController extends Controller
{
    public function index()
    {
        return Inertia::render('Treatment', [
            //
        ]);
    }
}
