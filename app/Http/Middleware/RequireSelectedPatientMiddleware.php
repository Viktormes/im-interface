<?php

namespace App\Http\Middleware;

use App\Sessions\SelectedPatientSession;
use Closure;
use Illuminate\Http\Request;

class RequireSelectedPatientMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $selectedPatient = app(SelectedPatientSession::class);

        if (! $selectedPatient->has()) {
            return redirect('/');
        }

        return $next($request);
    }
}
