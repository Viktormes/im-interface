<?php

namespace App\Http\Controllers\Auth;

use App\Constants\System;
use App\Fhir\Base\Resource\DomainResource\Practitioner;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $practitioner = Practitioner::query()
            ->where('resource->identifier[*]->system', 'LIKE', '%"'.System::SwedishPersonalIdentityNumber.'"%')
            ->where('resource->identifier[*]->value', 'LIKE', '%"19123456-1234"%')
            ->first();

        if (is_null($practitioner)) {
            // Create practitioner
        }

        $user = User::where('authable_type', $practitioner::class)
            ->where('authable_id', $practitioner->_id)
            ->first();

        if (is_null($user)) {
            // Create connected user
            $user = User::forceCreate([
                'authable_type' => $practitioner::class,
                'authable_id' => $practitioner->_id,
            ]);
        }

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
